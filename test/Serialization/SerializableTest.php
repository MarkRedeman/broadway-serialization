<?php

namespace BroadwaySerialization\Test\Serialization;

use BroadwaySerialization\Hydration\HydrateUsingReflection;
use BroadwaySerialization\Reconstitution\ReconstituteUsingInstantiatorAndHydrator;
use BroadwaySerialization\Reconstitution\Reconstitution;
use BroadwaySerialization\Test\Serialization\Fixtures\SerializableAbstractClass;
use BroadwaySerialization\Test\Serialization\Fixtures\SerializableAbstractClassWithPrivates;
use BroadwaySerialization\Test\Serialization\Fixtures\SerializableChildOfClassWithPrivates;
use BroadwaySerialization\Test\Serialization\Fixtures\SerializableChildWithPrivates;
use BroadwaySerialization\Test\Serialization\Fixtures\SerializableObjectUsingTrait;
use BroadwaySerialization\Test\Serialization\Fixtures\SerializableObjectWithNoCallbacks;
use BroadwaySerialization\Test\Serialization\Fixtures\SerializableObjectWithParent;
use BroadwaySerialization\Test\Serialization\Fixtures\SerializableSimpleObjectWithParent;
use BroadwaySerialization\Test\Serialization\Fixtures\TraditionalSerializableObject;
use Doctrine\Instantiator\Instantiator;

class SerializableTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        Reconstitution::reconstituteUsing(
            new ReconstituteUsingInstantiatorAndHydrator(new Instantiator(), new HydrateUsingReflection())
        );
    }

    /**
     * @test
     */
    public function it_serializes_and_deserializes_objects_successfully()
    {
        $originalObject = new SerializableObjectUsingTrait(
            // simple scalar value
            'foo',
            // object
            new TraditionalSerializableObject('baz'),
            // array of objects
            [
                new TraditionalSerializableObject('baz'),
                new TraditionalSerializableObject('baz')
            ]
        );

        $data = $originalObject->serialize();
        $reconstitutedInstance = SerializableObjectUsingTrait::deserialize($data);

        $this->assertEquals($originalObject, $reconstitutedInstance);
    }

    /**
     * @test
     */
    public function it_has_no_custom_callbacks_by_default()
    {
        $originalObject = new SerializableObjectWithNoCallbacks('baz');

        $data = $originalObject->serialize();
        $reconstitutedInstance = SerializableObjectWithNoCallbacks::deserialize($data);

        $this->assertEquals($originalObject, $reconstitutedInstance);
    }

    /**
     * @test
     */
    public function it_serializes_objects_inheriting_from_a_serializable_object()
    {
        $originalObject = new SerializableSimpleObjectWithParent(
            new TraditionalSerializableObject('baz'),
            42
        );

        $data = $originalObject->serialize();

        $reconstitutedInstance = SerializableSimpleObjectWithParent::deserialize($data);

        $this->assertEquals($originalObject, $reconstitutedInstance);
    }

    /**
     * @test
     */
    public function it_uses_the_callbacks_of_a_child_class()
    {
        $originalObject = new SerializableObjectWithParent(
            new TraditionalSerializableObject('foo'),
            new TraditionalSerializableObject('baz'),
            42
        );

        $data = $originalObject->serialize();
        $reconstitutedInstance = SerializableObjectWithParent::deserialize($data);

        $this->assertEquals($originalObject, $reconstitutedInstance);
    }

    /**
     * @test
     */
    public function it_does_not_serialize_private_properties_of_a_child_class()
    {
        $originalObject = new SerializableChildWithPrivates(
            // Protected property foo of parent
            new TraditionalSerializableObject('foo'),
            // 42 is the private property "baz"
            42
        );

        $data = $originalObject->serialize();
        $reconstitutedInstance = SerializableChildWithPrivates::deserialize($data);

        // Check that the private property hasn't been serialized correctly
        $this->assertArrayNotHasKey("baz", $data);

        // As a result the original object and reconstituted object are not equal
        $this->assertNotEquals(
            $originalObject->baz(),
            $reconstitutedInstance->baz(),
            "Serializing private properties of a child class is not (yet) supported"
        );
    }

    /**
     * @test
     */
    public function it_does_not_deserialize_private_properties_of_a_parent_class()
    {
        $originalObject = new SerializableChildOfClassWithPrivates(
            // private property 'foo' of parent
            'foo'
        );

        $data = $originalObject->serialize();
        $reconstitutedInstance = SerializableChildOfClassWithPrivates::deserialize($data);

        // Serializing private properties works
        $this->assertArrayHasKey('foo', $data);

        // Deserializing a private property of a parent class does not work
        $this->assertNotEquals(
            $originalObject->foo(),
            $reconstitutedInstance->foo(),
            "Serializing private properties of a parent class is not (yet) supported"
        );
    }
}
