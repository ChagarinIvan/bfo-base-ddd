<?php

declare(strict_types=1);

namespace App\Bridge\Laravel\Http\Controller\Cup;

use App\Application\Dto\Cup\CupDto;
use App\Application\Dto\Cup\CupSearchDto;
use App\Application\Dto\Cup\ViewCupDto;
use App\Application\Dto\Shared\PaginationAdapter;
use App\Application\Dto\Shared\TokenFootprint;
use App\Application\Service\Cup\AddCup;
use App\Application\Service\Cup\AddCupService;
use App\Application\Service\Cup\DisableCup;
use App\Application\Service\Cup\DisableCupService;
use App\Application\Service\Cup\Exception\CupNotFound;
use App\Application\Service\Cup\ListCups;
use App\Application\Service\Cup\ListCupsService;
use App\Application\Service\Cup\UpdateCup;
use App\Application\Service\Cup\UpdateCupInfoService;
use App\Application\Service\Cup\ViewCup;
use App\Application\Service\Cup\ViewCupService;
use App\Bridge\Laravel\Http\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class CupController extends Controller
{
    public function view(string $id, ViewCupService $service): ViewCupDto
    {
        try {
            return $service->execute(new ViewCup($id));
        } catch (CupNotFound) {
            throw new NotFoundHttpException();
        }
    }

    public function list(CupSearchDto $search, ListCupsService $service): PaginationAdapter
    {
        return $service->execute(new ListCups($search));
    }

    public function create(
        CupDto $cup,
        AddCupService $service,
        TokenFootprint $token,
    ): ViewCupDto {
        return $service->execute(new AddCup($cup, $token));
    }

    public function changeCupInfo(
        string $id,
        CupDto $info,
        UpdateCupInfoService $service,
        TokenFootprint $token,
    ): Response {
        try {
            $service->execute(new UpdateCup($id, $info, $token));
        } catch (CupNotFound) {
            throw new NotFoundHttpException();
        }

        return new Response(status: Response::HTTP_NO_CONTENT);
    }

    public function disable(
        string $id,
        DisableCupService $service,
        TokenFootprint $token,
    ): Response {
        try {
            $service->execute(new DisableCup($id, $token));
        } catch (CupNotFound) {
            throw new NotFoundHttpException();
        }

        return new Response(status: Response::HTTP_NO_CONTENT);
    }
}
