<?php

namespace BroadwaySerialization\Test\Serialization\Fixtures;

use Broadway\Serializer\SerializableInterface;
use BroadwaySerialization\Serialization\Serializable;

abstract class SerializableAbstractClass implements SerializableInterface
{
    use Serializable;

    protected $foo;

    public function __construct(TraditionalSerializableObject $foo)
    {
        $this->foo = $foo;
    }

    protected static function deserializationCallbacks()
    {
        return [
            'foo' => ['BroadwaySerialization\Test\Serialization\Fixtures\TraditionalSerializableObject', 'deserialize']
        ];
    }
}
