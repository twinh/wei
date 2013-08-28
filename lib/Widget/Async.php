<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget;

/**
 * A queue widget
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Async extends Base
{
    /**
     * The name of queue
     *
     * @var string
     */
    protected $queue = 'widget';

    /**
     * The block seconds for `blPop` method
     *
     * @var int
     */
    protected $blockSeconds = 2;

    /**
     * The seconds to interrupt for next callback
     *
     * @var float
     */
    //protected $waitSeconds = 2;

    /**
     * The heartbeat timeout seconds
     *
     * @var int
     */
    protected $timeout = 70;

    /**
     * The command to start the queue server
     *
     * @var string
     */
    protected $command;

    /**
     * The queue server running status
     *
     * @var array
     */
    protected $stat = array(
        'startTime' => null,
        'heartbeat' => null,
        'pid'       => null,
    );

    /**
     * Add a callback to the queue
     *
     * @param callable $fn when fail to add item to the queue
     * @throws \InvalidArgumentException when parameter is not callable
     * @throws \RuntimeException
     * @return Async
     */
    public function __invoke($fn)
    {
        if (!is_callable($fn)) {
            throw new \InvalidArgumentException('Parameter for async must be callable');
        }

        $data = $this->serializeClosure($fn);

        // TODO redis 连接超时,塞入失败上报到网管
        $result = $this->push($data);
        if (!$result) {
            throw new \RuntimeException('Fail to add item to the queue');
        }

        return $this;
    }

    /**
     * Run the queue server
     *
     * @throws \Exception|\RedisException
     */
    public function runServer()
    {
        $logger = $this->logger;
        $this->setStat('startTime', date('Y-m-d H:i:s'));

        while(true) {

            $this->setHeartbeat();
            //usleep($this->waitSeconds * 1000000);

            try {
                // Receive content form queue server
                $fn = $this->pop($this->queue);
                if (empty($fn)) {
                    $logger->debug('Received empty queue content from Redis');
                    continue;
                } else {
                    $logger->debug('Received queue content: ' . var_export($fn, true));
                }

                $result = $this->callSerializedClosure($fn);

                $logger->debug('Executed callback result: ' . var_export($result, true));
            } catch (\RedisException $e) {
                $logger->alert('Caught Redis exception with message :' . $e->getMessage(), array('exception' => $e));
                throw $e;
            } catch(\Exception $e) {
                $this->logger->alert((string)$e);
                //throw $e;
            }
        }
    }

    /**
     * Run the daemon server
     */
    public function runDaemon()
    {
        $logger = $this->logger;
        $result = null;

        if ($this->isRunning()) {
            $logger->debug('Queue server is running');
        } else {
            $logger->alert('Queue server is not running, restarting...');
            $result = $this->execCommand();
        }

        if (!$this->isHeartbeatTimeout()) {
            $logger->debug('Queue server heartbeat is OK');
        } else {
            $logger->alert('Heartbeat is timeout, restarting...');

            // Stop previous process
            if ($this->isRunning()) {
                $result = $this->stopServer();
                if ($result) {
                    $logger->debug('Stopped redis queue server');
                } else {
                    $logger->alert('Fail to kill server pid');
                }
            }

            // Start queue server
            $result = $this->execCommand();
        }

        if ($result) {
            $logger->debug(sprintf('started with result %s', implode("\n", $result)));
        }
    }

    protected function execCommand()
    {
        // Start queue server
        $result = null;
        exec($this->command, $result);
        $this->setPid($result[0]);

        $this->setHeartbeat();

        return $result;
    }

    /**
     * Check if heartbeat timeout
     *
     * @return bool
     */
    protected function isHeartbeatTimeout()
    {
        $heartbeat = $this->redis->get($this->queue . '-heartbeat');
        return time() - strtotime($heartbeat) > $this->timeout;
    }

    /**
     * Execute the queue
     *
     * @param array $argv
     */
    public function run($argv)
    {
        $action = isset($argv[1]) ? $argv[1] : null;

        if (isset($argv[2])) {
            $this->setQueue($argv[2]);
        }

        switch($action) {
            case 'start':
                $this->runServer();
                break;

            case 'start-daemon':
                $this->runDaemon();
                $result = 'Executed daemon';
                break;

            case 'stop':
                $result = $this->stopServer() ?
                    'Stopped redis queue' : 'Fail to kill server pid';
                break;

            case 'stat':
                $result = $this->displayStat();
                break;

            default :
                $result = 'Usages: start-daemon, start, stop or stat.';
                break;
        }
        fwrite(STDOUT, $result . PHP_EOL);
    }

    /**
     * Kill the pid to stop the redis queue server
     *
     * @return bool return false when fail to kill the pid
     * @throws \Exception when pid is not set in redis server
     */
    protected function stopServer()
    {
        $pid = $this->getPid();
        if (!$pid) {
            throw new \Exception('Pid is not set in redis server');
        }

        exec('kill ' . $pid);

        if (!$this->isRunning()) {
            $this->setPid(null);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check if the process is running
     *
     * @return bool
     */
    public function isRunning()
    {
        $command = 'ps -p '. $this->getPid();
        $result = null;

        /**
         * Running:
         * $result = array(
         *     'PID TTY          TIME CMD',
         *     '8839 pts/5    00:00:00 php'
         * );
         *
         * Not running:
         * $result = array(
         *     'PID TTY          TIME CMD',
         * );
         */
        exec($command, $result);

        return isset($result[1]);
    }

    protected function setCommand($command)
    {
        $this->command = 'nohup '. $command .' > /dev/null 2>&1 & echo $!';
        return $this;
    }

    /**
     * Returns the server stat
     *
     * @return array
     */
    public function getStat()
    {
        $redis = $this->redis->getObject();

        return array(
            'queue'         => $this->queue,
            'totalItems'    => $redis->get($this->queue . '-count'),
            'remainItems'   => $redis->lLen($this->queue),
            'pid'           => $redis->get($this->queue . '-pid'),
            'startTime'     => $redis->get($this->queue . '-startTime'),
            'heartbeat'     => $redis->get($this->queue . '-heartbeat'),
            'now'           => date('Y-m-d H:i:s'),
        );
    }

    protected function displayStat()
    {
        $result = "\n";
        $stat = $this->getStat() + $this->stat;
        foreach ($stat as $name => $value) {
            $result .= sprintf("%s: %s\n", $name, $value);
        }
        return $result;
    }

    /**
     * Set server running status
     *
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function setStat($name, $value)
    {
        $this->stat[$name] = $value;
        $this->redis->set($this->queue . '-' . $name, $value);
        return $this;
    }

    /**
     * Set heartbeat time
     */
    protected function setHeartbeat()
    {
        $this->setStat('heartbeat', date('Y-m-d H:i:s'));
    }

    /**
     * Set the process ID
     *
     * @param string $pid
     */
    protected function setPid($pid)
    {
        $this->setStat('pid', $pid);
    }

    /**
     * Returns the queue server process ID
     *
     * @return string
     */
    public function getPid()
    {
        return $this->redis->get($this->queue . '-pid');
    }

    /**
     * Sets the queue name
     *
     * @param string $name
     * @return $this
     */
    public function setQueue($name)
    {
        $this->queue = $name;
        return $this;
    }

    /**
     * Returns the queue name
     *
     * @return string
     */
    public function getQueue()
    {
        return $this->queue;
    }

    /**
     * Add a item to the queue
     *
     * @param string $item
     * @return int
     */
    protected function push($item)
    {
        $redis = $this->redis->getObject();
        $redis->incr($this->queue . '-count', 1);
        return $redis->lPush($this->queue, $item);
    }

    /**
     * Remove and get the first item in the queue, or block until it is available
     *
     * @return string|false
     */
    protected function pop()
    {
        $content = $this->redis->getObject()->blPop($this->queue, $this->blockSeconds);
        return ($content && isset($content[1])) ? $content[1] : false;
    }

    /**
     * Serialize callable variable to string
     *
     * @param callable $fn
     * @return string
     * @throws \InvalidArgumentException
     */
    public function serializeClosure($fn)
    {
        if (!$fn instanceof \Closure && !is_array($fn) && !is_string($fn)) {
            throw new \InvalidArgumentException('Argument must be instance of Closure, array or string');
        }

        if ($fn instanceof \Closure) {
            $func = new \ReflectionFunction($fn);
            $filename = $func->getFileName();
            $start_line = $func->getStartLine() - 1; // it's actually - 1, otherwise you wont get the function() block
            $end_line = $func->getEndLine();
            $length = $end_line - $start_line;

            $source = file($filename);
            $body = implode("", array_slice($source, $start_line, $length));

            $begin = strpos($body, 'function(');

            $body = substr($body, $begin, strrpos($body, '}') - $begin + 1) . ';';

            $useVariables = $func->getStaticVariables();

            return serialize(array($body, $useVariables));
        } else {
            return serialize(array($fn, false));
        }
    }

    /**
     * Call a serialized Closure
     *
     * @param string $_context
     * @return mixed
     */
    public function callSerializedClosure($_context)
    {
        list($_fn, $_useVars) = unserialize($_context);

        $_useVars && extract($_useVars);

        if (!is_callable($_fn)) {
            @eval('$_fn = ' . $_fn);
            if ($e = error_get_last()) {
                $this->logger->alert('Eval queue callback error', $e);
            }
        }

        return call_user_func($_fn);
    }
}