<?php

namespace Wei\Model;

trait CamelCaseTrait
{
    public static function bootCamelCaseTrait(): void
    {
        static::onModelEvent('init', 'setCamelCaseKeyConverter');
    }

    public function setCamelCaseKeyConverter(): void
    {
        $this->setDbKeyConverter([$this->wei->str, 'snake']);
        $this->setPhpKeyConverter([$this->wei->str, 'camel']);
    }
}
