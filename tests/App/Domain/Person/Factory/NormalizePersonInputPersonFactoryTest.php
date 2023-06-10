<?php

declare(strict_types=1);

namespace Tests\Domain\Person\Factory;

use App\Domain\Person\Factory\NormalizePersonInfoPersonFactory;
use App\Domain\Person\Factory\PersonFactory;
use App\Domain\Person\Factory\PersonInput;
use App\Domain\Person\PersonInfo;
use App\Domain\Person\PersonInfoNormalizer;
use App\Domain\Shared\Metadata;
use App\Domain\Shared\Normalizer\Normalizer;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tests\Faker\Person\PersonFaker;
use Tests\Faker\Shared\AuthFaker;

class NormalizePersonInputPersonFactoryTest extends TestCase
{
    private readonly PersonFactory&MockObject $decorated;

    private readonly Normalizer&MockObject $normalizer;

    private readonly NormalizePersonInfoPersonFactory $factory;

    protected function setUp(): void
    {
        $this->factory = new NormalizePersonInfoPersonFactory(
            $this->decorated = $this->createMock(PersonFactory::class),
            new PersonInfoNormalizer(
                $this->normalizer = $this->createMock(Normalizer::class),
            )
        );
    }

    /** @test */
    public function it_normalizes_person_info(): void
    {
        $baseInput = new PersonInput(
            new PersonInfo(
                'Test firstname',
                'Test lastname',
                1988,
            ),
            Metadata::empty(),
            AuthFaker::fakeFootprint()
        );

        $expectedInput = new PersonInput(
            new PersonInfo(
                'normalized firstname',
                'normalized lastname',
                1988,
            ),
            Metadata::empty(),
            AuthFaker::fakeFootprint()
        );

        $this->decorated
            ->expects($this->once())
            ->method('create')
            ->with($this->equalTo($expectedInput))
            ->willReturn(PersonFaker::fakePerson())
        ;

        $this->normalizer
            ->expects($this->exactly(2))
            ->method('normalize')
            ->willReturnOnConsecutiveCalls('normalized firstname', 'normalized lastname')
        ;

        $this->factory->create($baseInput);
    }
}
