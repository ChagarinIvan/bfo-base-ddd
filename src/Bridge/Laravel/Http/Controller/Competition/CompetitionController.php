<?php

declare(strict_types=1);

namespace App\Bridge\Laravel\Http\Controller\Competition;

use App\Application\Dto\Competition\CompetitionInfoDto;
use App\Application\Dto\Competition\CompetitionSearchDto;
use App\Application\Dto\Competition\ViewCompetitionDto;
use App\Application\Dto\Shared\PaginationAdapter;
use App\Application\Dto\Shared\TokenFootprint;
use App\Application\Service\Competition\AddCompetition;
use App\Application\Service\Competition\AddCompetitionService;
use App\Application\Service\Competition\DisableCompetition;
use App\Application\Service\Competition\DisableCompetitionService;
use App\Application\Service\Competition\Exception\CompetitionNotFound;
use App\Application\Service\Competition\ListCompetitions;
use App\Application\Service\Competition\ListCompetitionsService;
use App\Application\Service\Competition\UpdateCompetitionInfo;
use App\Application\Service\Competition\UpdateCompetitionInfoService;
use App\Application\Service\Competition\ViewCompetition;
use App\Application\Service\Competition\ViewCompetitionService;
use App\Bridge\Laravel\Http\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class CompetitionController extends Controller
{
    public function view(string $id, ViewCompetitionService $service): ViewCompetitionDto
    {
        try {
            return $service->execute(new ViewCompetition($id));
        } catch (CompetitionNotFound) {
            throw new NotFoundHttpException();
        }
    }

    public function list(CompetitionSearchDto $search, ListCompetitionsService $service): PaginationAdapter
    {
        return $service->execute(new ListCompetitions($search));
    }

    public function create(
        CompetitionInfoDto $info,
        AddCompetitionService $service,
        TokenFootprint $token,
    ): ViewCompetitionDto {
        return $service->execute(new AddCompetition($info, $token));
    }

    public function changeCompetitionInfo(
        string $id,
        CompetitionInfoDto $info,
        UpdateCompetitionInfoService $service,
        TokenFootprint $token,
    ): Response {
        try {
            $service->execute(new UpdateCompetitionInfo($id, $info, $token));
        } catch (CompetitionNotFound) {
            throw new NotFoundHttpException();
        }

        return new Response(status: Response::HTTP_NO_CONTENT);
    }

    public function disable(
        string $id,
        DisableCompetitionService $service,
        TokenFootprint $token,
    ): Response {
        try {
            $service->execute(new DisableCompetition($id, $token));
        } catch (CompetitionNotFound) {
            throw new NotFoundHttpException();
        }

        return new Response(status: Response::HTTP_NO_CONTENT);
    }
}
