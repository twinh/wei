<?php

namespace Wei;

/**
 * Sends statistics to the stats daemon over UDP
 *
 * @link https://github.com/etsy/statsd/blob/master/examples/php-example.php
 * @link https://github.com/domnikl/statsd-php/blob/master/lib/Client.php
 */
class StatsD extends Base
{
    /**
     * @var string
     */
    protected $host = '127.0.0.1';

    /**
     * @var int
     */
    protected $port = 8125;

    /**
     * @var bool
     */
    protected $enable = true;

    /**
     * Holds all the timings that have not yet been completed
     *
     * @var array
     */
    protected $timings = [];

    /**
     * Holds all memory profiles like timings
     *
     * @var array
     */
    private $memoryProfiles = [];

    /**
     * Sets one or more timing values
     *
     * @param string|array $key the metric(s) to set
     * @param float $time The elapsed time (ms) to log
     * @param int|float $sampleRate the rate (0-1) for sampling
     */
    public function timing($key, $time, $sampleRate = 1)
    {
        $this->updateStats($key, $time, $sampleRate, 'ms');
    }

    /**
     * Starts the timing for a key
     *
     * @param string $key
     */
    public function startTiming($key)
    {
        $this->timings[$key] = gettimeofday(true);
    }

    /**
     * Ends the timing for a key and sends it to StatsD
     *
     * @param string $key
     * @param int|float $sampleRate the rate (0-1) for sampling
     * @return float|null
     */
    public function endTiming($key, $sampleRate = 1)
    {
        $end = gettimeofday(true);
        if (isset($this->timings[$key])) {
            $timing = ($end - $this->timings[$key]) * 1000;
            $this->timing($key, $timing, $sampleRate);
            unset($this->timings[$key]);
            return $timing;
        }
        return null;
    }

    /**
     * Executes a Closure and records it's execution time and sends it to StatsD
     * returns the value the Closure returned
     *
     * @param string $key
     * @param \Closure $fn
     * @param int|float $sampleRate the rate (0-1) for sampling
     * @return mixed
     */
    public function time($key, \Closure $fn, $sampleRate = 1)
    {
        $this->startTiming($key);
        $return = $fn();
        $this->endTiming($key, $sampleRate);
        return $return;
    }

    /**
     * Start memory "profiling"
     *
     * @param string $key
     */
    public function startMemoryProfile($key)
    {
        $this->memoryProfiles[$key] = memory_get_usage();
    }

    /**
     * Ends the memory profiling and sends the value to the server
     *
     * @param string $key
     * @param int|float $sampleRate the rate (0-1) for sampling
     */
    public function endMemoryProfile($key, $sampleRate = 1)
    {
        $end = memory_get_usage();
        if (array_key_exists($key, $this->memoryProfiles)) {
            $memory = ($end - $this->memoryProfiles[$key]);
            $this->memory($key, $memory, $sampleRate);
            unset($this->memoryProfiles[$key]);
        }
    }

    /**
     * Report memory usage to StatsD. if memory was not given report peak usage
     *
     * @param string $key
     * @param int $memory
     * @param int|float $sampleRate the rate (0-1) for sampling
     */
    public function memory($key, $memory = null, $sampleRate = 1)
    {
        if (null === $memory) {
            $memory = memory_get_peak_usage();
        }
        $this->count($key, $memory, $sampleRate);
    }

    /**
     * Sets one or more gauges to a value
     *
     * @param string|array $key the metric(s) to set
     * @param float $value the value for the stats
     */
    public function gauge($key, $value)
    {
        $this->updateStats($key, $value, 1, 'g');
    }

    /**
     * A "Set" is a count of unique events.
     * This data type acts like a counter, but supports counting
     * of unique occurrences of values between flushes. The backend
     * receives the number of unique events that happened since
     * the last flush.
     *
     * The reference use case involved tracking the number of active
     * and logged in users by sending the current userId of a user
     * with each request with a key of "uniques" (or similar).
     *
     * @param string|array $key the metric(s) to set
     * @param float $value the value for the stats
     */
    public function set($key, $value)
    {
        $this->updateStats($key, $value, 1, 's');
    }

    /**
     * Increments one or more stats counters
     *
     * @param string|array $key the metric(s) to increment
     * @param int|float $sampleRate the rate (0-1) for sampling
     */
    public function increment($key, $sampleRate = 1)
    {
        $this->updateStats($key, 1, $sampleRate, 'c');
    }

    /**
     * Decrements one or more stats counters.
     *
     * @param string|array $key the metric(s) to decrement
     * @param float|int $sampleRate the rate (0-1) for sampling
     */
    public function decrement($key, $sampleRate = 1)
    {
        $this->updateStats($key, -1, $sampleRate, 'c');
    }

    /**
     * Sends a count to StatsD
     *
     * @param string $key
     * @param int $value
     * @param int $sampleRate (optional) the default is 1
     */
    public function count($key, $value, $sampleRate = 1)
    {
        $this->updateStats($key, (int) $value, $sampleRate, 'c');
    }

    /**
     * Updates one or more stats.
     *
     * @param string|array $key The metric(s) to update. Should be either a string or array of metrics.
     * @param int $delta the amount to increment/decrement each metric by
     * @param float|int $sampleRate the rate (0-1) for sampling
     * @param string $metric The metric type ("c" for count, "ms" for timing, "g" for gauge, "s" for set)
     */
    public function updateStats($key, $delta = 1, $sampleRate = 1, $metric = 'c')
    {
        if (!is_array($key)) {
            $key = [$key];
        }
        $data = [];
        foreach ($key as $stat) {
            $data[$stat] = "$delta|$metric";
        }
        $this->send($data, $sampleRate);
    }

    /**
     * Squirt the metrics over UDP
     *
     * @param array $data
     * @param int|float $sampleRate
     */
    public function send(array $data, $sampleRate = 1)
    {
        if (!$this->enable) {
            return;
        }

        // sampling
        $sampledData = [];
        if ($sampleRate < 1) {
            foreach ($data as $stat => $value) {
                if ((mt_rand() / mt_getrandmax()) <= $sampleRate) {
                    $sampledData[$stat] = "$value|@$sampleRate";
                }
            }
        } else {
            $sampledData = $data;
        }
        if (empty($sampledData)) {
            return;
        }

        $handle = fsockopen('udp://' . $this->host, $this->port, $errno, $errstr);
        if (!$handle) {
            return;
        }
        foreach ($sampledData as $stat => $value) {
            fwrite($handle, $stat . ':' . $value);
        }
        fclose($handle);
    }
}
