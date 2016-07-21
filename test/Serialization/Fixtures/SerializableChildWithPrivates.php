<?php

namespace BroadwaySerialization\Test\Serialization\Fixtures;

class SerializableChildWithPrivates extends SerializableAbstractClass
{
    private $baz;

    public function __construct(TraditionalSerializableObject $foo, $baz)
    {
        parent::__construct($foo);
        $this->baz = $baz;
    }

    public function baz()
    {
        return $this->baz;
    }
}
