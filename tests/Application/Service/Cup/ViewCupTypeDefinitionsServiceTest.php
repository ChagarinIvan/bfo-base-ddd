<?php

declare(strict_types=1);

namespace Tests\Application\Service\Cup;

use App\Application\Dto\Cup\CupAssembler;
use App\Application\Dto\Cup\ViewCupTypeDefinitionDto;
use App\Application\Dto\Shared\AuthAssembler;
use App\Application\Service\Cup\ViewCupTypeDefinitionsService;
use App\Domain\Cup\CupTypeDefinitions;
use PHPUnit\Framework\TestCase;

class ViewCupTypeDefinitionsServiceTest extends TestCase
{
    private ViewCupTypeDefinitionsService $service;

    protected function setUp(): void
    {
        $this->service = new ViewCupTypeDefinitionsService(
            new CupTypeDefinitions(),
            new CupAssembler(new AuthAssembler()),
        );
    }

    /** @test */
    public function it_views_cup_type_definitions(): void
    {
        $definitions = $this->service->execute();

        $this->assertContainsOnlyInstancesOf(ViewCupTypeDefinitionDto::class, $definitions);
        $this->assertCount(7, $definitions);
    }
}
