<?php

namespace BroadwaySerialization\Test\Performance;

use Broadway\Serializer\SerializableInterface;

class TraditionalSerializableClass implements SerializableInterface
{
    private $stringProperty = 'foo';
    private $integerProperty = 20;
    private $nullProperty = null;
    private $arrayProperty = ['foo' => 'bar', 'bar' => 'baz'];
    private $objectProperty;
    private $objectsProperty;

    public function __construct()
    {
        $this->objectProperty = new SomeOtherSerializableClass('foo');
        $this->objectsProperty = [
            new SomeOtherSerializableClass('bar'),
            new SomeOtherSerializableClass('baz'),
        ];
    }

    public static function deserialize(array $data)
    {
        $object = new self();

        $object->stringProperty = $data['stringProperty'];
        $object->integerProperty = $data['integerProperty'];
        $object->nullProperty = $data['nullProperty'];
        $object->arrayProperty = $data['arrayProperty'];
        $object->objectProperty = is_array($data['objectProperty']) ? SomeOtherSerializableClass::deserialize($data['objectProperty']) : null;
        $object->objectsProperty = array_map(
            function ($data) {
                return SomeOtherSerializableClass::deserialize($data);
            },
            $data['objectsProperty']
        );

        return $object;
    }

    public function serialize()
    {
        return [
            'stringProperty' => $this->stringProperty,
            'integerProperty' => $this->integerProperty,
            'nullProperty' => $this->nullProperty,
            'arrayProperty' => $this->arrayProperty,
            'objectProperty' => $this->objectProperty->serialize(),
            'objectsProperty' => array_map(function (SomeOtherSerializableClass $object) {
                return $object->serialize();
            }, $this->objectsProperty)
        ];
    }
}
