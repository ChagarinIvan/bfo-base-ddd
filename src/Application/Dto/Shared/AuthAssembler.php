<?php

declare(strict_types=1);

namespace App\Application\Dto\Shared;

use App\Domain\Shared\Footprint;
use App\Domain\Shared\Impression;

final class AuthAssembler
{
    public function toImpressionDto(Impression $impression): ImpressionDto
    {
        $dto = new ImpressionDto();
        $dto->at = $impression->at;
        $dto->by = $this->toFootprintDto($impression->by);

        return $dto;
    }

    private function toFootprintDto(Footprint $footprint): FootprintDto
    {
        $dto = new FootprintDto();
        $dto->id = $footprint->id->toString();

        return $dto;
    }
}
