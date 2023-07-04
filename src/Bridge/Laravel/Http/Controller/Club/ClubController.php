<?php

declare(strict_types=1);

namespace App\Bridge\Laravel\Http\Controller\Club;

use App\Application\Dto\Club\ClubDto;
use App\Application\Dto\Club\ClubSearchDto;
use App\Application\Dto\Club\ViewClubDto;
use App\Application\Dto\Shared\PaginationAdapter;
use App\Application\Dto\Shared\TokenFootprint;
use App\Application\Service\Club\AddClub;
use App\Application\Service\Club\AddClubService;
use App\Application\Service\Club\DisableClub;
use App\Application\Service\Club\DisableClubService;
use App\Application\Service\Club\Exception\ClubNotFound;
use App\Application\Service\Club\Exception\FailedToAddClub;
use App\Application\Service\Club\ListClubs;
use App\Application\Service\Club\ListClubsService;
use App\Application\Service\Club\UpdateClub;
use App\Application\Service\Club\UpdateClubService;
use App\Application\Service\Club\ViewClub;
use App\Application\Service\Club\ViewClubService;
use App\Bridge\Laravel\Http\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class ClubController extends Controller
{
    public function view(string $id, ViewClubService $service): ViewClubDto
    {
        try {
            return $service->execute(new ViewClub($id));
        } catch (ClubNotFound) {
            throw new NotFoundHttpException();
        }
    }

    public function list(ClubSearchDto $search, ListClubsService $service): PaginationAdapter
    {
        return $service->execute(new ListClubs($search));
    }

    public function create(
        ClubDto $data,
        AddClubService $service,
        TokenFootprint $token,
    ): ViewClubDto {
        try {
            return $service->execute(new AddClub($data, $token));
        } catch (FailedToAddClub) {
            throw new ConflictHttpException();
        }
    }

    public function changeClub(
        string $id,
        ClubDto $data,
        UpdateClubService $service,
        TokenFootprint $token,
    ): Response {
        try {
            $service->execute(new UpdateClub($id, $data, $token));
        } catch (ClubNotFound) {
            throw new NotFoundHttpException();
        }

        return new Response(status: Response::HTTP_NO_CONTENT);
    }

    public function disable(
        string $id,
        DisableClubService $service,
        TokenFootprint $token,
    ): Response {
        try {
            $service->execute(new DisableClub($id, $token));
        } catch (ClubNotFound) {
            throw new NotFoundHttpException();
        }

        return new Response(status: Response::HTTP_NO_CONTENT);
    }
}
