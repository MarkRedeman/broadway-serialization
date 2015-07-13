<?php

namespace BroadwaySerialization\Test\SymfonyIntegration;

use BroadwaySerialization\Reconstitution\Reconstitution;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BroadwaySerializationBundleTest extends KernelTestCase
{
    protected static function getKernelClass()
    {
        return 'BroadwaySerialization\Test\SymfonyIntegration\TestKernel';
    }

    /**
     * @test
     */
    public function it_properly_configures_the_reconstitute_service()
    {
        // reset previous state
        Reconstitution::reconstituteUsing(null);

        $kernel = $this->createKernel();
        $kernel->boot();

        $this->assertSame(
            $kernel->getContainer()->get('broadway_serialization.reconstitute'),
            Reconstitution::reconstitute()
        );
    }
}
