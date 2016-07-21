<?php

namespace BroadwaySerialization\Test\Serialization\Fixtures;

class SerializableSimpleObjectWithParent extends SerializableAbstractClass
{
    protected $bar;

    public function __construct(TraditionalSerializableObject $foo, $bar)
    {
        parent::__construct($foo);
        $this->bar = $bar;
    }
}
