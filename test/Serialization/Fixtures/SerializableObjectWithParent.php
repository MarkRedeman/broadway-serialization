<?php

namespace BroadwaySerialization\Test\Serialization\Fixtures;

class SerializableObjectWithParent extends SerializableAbstractClass
{
    protected $bar;
    protected $baz;

    public function __construct(TraditionalSerializableObject $foo, TraditionalSerializableObject $baz, $bar)
    {
        parent::__construct($foo);
        $this->baz = $baz;
        $this->bar = $bar;
    }

    protected static function deserializationCallbacks()
    {
        return array_merge(
            parent::deserializationCallbacks(),
            [
                'baz' => ['BroadwaySerialization\Test\Serialization\Fixtures\TraditionalSerializableObject', 'deserialize']
            ]
        );
    }
}
