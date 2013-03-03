<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Serializer;

interface SerializerInterface
{
    /**
     * Encodes data
     * 
     * @param mixed $data
     * @return mixed
     */
    public function encode($data);

    /**
     * Decodes data
     * 
     * @param mixed $data
     * @return mixed
     */
    public function decode($data);
}
