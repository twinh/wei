<?php

namespace Wei;

/**
 * @mixin \CacheMixin
 */
class Snowflake extends Base
{
    /**
     * The start timestamp in milliseconds, the default value is "2021-09-01" in milliseconds
     *
     * @var int
     */
    protected $startTimestamp = 1630425600000;

    /**
     * 机器位数，10 位共 1024 台
     *
     * @var int
     */
    protected $workerBits = 10;

    /**
     * 每毫秒最多能生成的序列位数，12 位共 4096 个
     *
     * @var int
     */
    protected $sequenceBits = 12;

    /**
     * 当前的机器(服务器，进程)编号
     *
     * @var int|null
     */
    protected $workerId;

    /**
     * 是否随机生成开始的序列数，如果为 false 则从 0 开始
     *
     * 优点
     * 1. 减少相同编号 worker 生成的序列数冲突
     * 2. 避免序列数总是从 0 开始，取模不均匀
     * 3. 提高数据量估算难度
     *
     * 缺点
     * 1. 序列数可用长度减少一半
     * 2. 增加等待下一毫秒的几率
     *
     * @var bool
     */
    protected $randomStartSequence = true;

    /**
     * @return int
     * @svc
     */
    protected function getWorkerId(): int
    {
        if (null === $this->workerId) {
            $this->workerId = mt_rand(0, $this->getMaxNumber($this->workerBits));
        }
        return $this->workerId;
    }

    /**
     * Set the worker id
     *
     * @param int $workerId
     * @return $this
     * @svc
     */
    protected function setWorkerId(int $workerId): self
    {
        if ($workerId < 0) {
            throw new \InvalidArgumentException('Worker ID must be greater than 0');
        }
        if ($workerId > $this->getMaxNumber($this->workerBits)) {
            throw new \InvalidArgumentException(
                'Worker ID must be less than or equal to ' . $this->getMaxNumber($this->workerBits)
            );
        }
        $this->workerId = $workerId;
        return $this;
    }

    /**
     * Return the start timestamp
     *
     * @return int
     * @svc
     */
    protected function getStartTimestamp(): int
    {
        return $this->startTimestamp;
    }

    /**
     * Set the start timestamp
     *
     * @param int $startTimestamp
     * @return $this
     * @svc
     */
    protected function setStartTimestamp(int $startTimestamp): self
    {
        if ($startTimestamp < 0) {
            throw new \InvalidArgumentException('Start timestamp must be greater than 0');
        }
        if ($startTimestamp > time() * 1000) {
            throw new \InvalidArgumentException('Start timestamp must be less than or equal to the current time');
        }
        $this->startTimestamp = $startTimestamp;
        return $this;
    }

    /**
     * Generate an id
     *
     * @return string
     * @svc
     */
    protected function next(): string
    {
        $timestamp = $this->getTimestamp();
        $sequence = $this->getSequence($timestamp);
        while ($sequence > $this->getMaxNumber($this->sequenceBits)) {
            usleep(1);
            $timestamp = $this->getTimestamp();
            $sequence = $this->getSequence($timestamp);
        }

        return (string) (($timestamp - $this->getStartTimestamp() << ($this->workerBits + $this->sequenceBits))
            | ($this->getWorkerId() << $this->sequenceBits)
            | $sequence);
    }

    /**
     * Parse the given id, return timestamp, worker ID and sequence
     *
     * @param string|int $id
     * @return array{timestamp: int, workerId: int, sequence: int}
     * @svc
     */
    protected function parse($id): array
    {
        $bin = decbin($id);
        return [
            'timestamp' => bindec(substr($bin, 0, -$this->workerBits - $this->sequenceBits)) + $this->startTimestamp,
            'workerId' => bindec(substr($bin, -$this->workerBits - $this->sequenceBits, $this->workerBits)),
            'sequence' => bindec(substr($bin, -$this->sequenceBits)),
        ];
    }

    /**
     * Return the current timestamp
     *
     * @return int
     */
    protected function getTimestamp(): int
    {
        return (int) (microtime(true) * 1000);
    }

    /**
     * Return the sequence of current timestamp
     *
     * @param int $timestamp
     * @return int
     */
    protected function getSequence(int $timestamp): int
    {
        $key = 'snowflake:' . $this->getWorkerId() . ':' . $timestamp;
        // TODO 考虑增加比例，减少跨毫秒的情况
        $startSequence = $this->randomStartSequence ? mt_rand(0, $this->getMaxNumber($this->sequenceBits)) : 0;
        if ($this->cache->add($key, $startSequence, 1)) {
            return $startSequence;
        }
        return $this->cache->incr($key);
    }

    /**
     * @param int $bits
     * @return int
     */
    protected function getMaxNumber(int $bits): int
    {
        return 2 ** $bits - 1;
    }
}
