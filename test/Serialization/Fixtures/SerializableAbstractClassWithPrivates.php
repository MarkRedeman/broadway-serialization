<?php

namespace BroadwaySerialization\Test\Serialization\Fixtures;

use Broadway\Serializer\SerializableInterface;
use BroadwaySerialization\Serialization\Serializable;

abstract class SerializableAbstractClassWithPrivates implements SerializableInterface
{
    use Serializable;

    private $foo;

    public function __construct($foo)
    {
        $this->foo = $foo;
    }

    public function foo()
    {
        return $this->foo;
    }
}
