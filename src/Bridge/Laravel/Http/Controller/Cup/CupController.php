<?php

declare(strict_types=1);

namespace App\Bridge\Laravel\Http\Controller\Cup;

use App\Application\Dto\Cup\CupDto;
use App\Application\Dto\Cup\ViewCupDto;
use App\Application\Dto\Shared\TokenFootprint;
use App\Application\Service\Cup\AddCup;
use App\Application\Service\Cup\AddCupService;
use App\Bridge\Laravel\Http\Controller\Controller;

final class CupController extends Controller
{
    public function create(
        CupDto $cup,
        AddCupService $service,
        TokenFootprint $token,
    ): ViewCupDto {
        return $service->execute(new AddCup($cup, $token));
    }
}
