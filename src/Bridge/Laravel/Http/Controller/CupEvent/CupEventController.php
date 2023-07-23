<?php

declare(strict_types=1);

namespace App\Bridge\Laravel\Http\Controller\CupEvent;

use App\Application\Dto\CupEvent\CalculateCupEventDto;
use App\Application\Dto\CupEvent\CupEventAttributesDto;
use App\Application\Dto\CupEvent\CupEventDto;
use App\Application\Dto\CupEvent\CupEventSearchDto;
use App\Application\Dto\CupEvent\ViewCupEventDto;
use App\Application\Dto\CupEvent\ViewCupEventPointsDto;
use App\Application\Dto\Shared\PaginationAdapter;
use App\Application\Dto\Shared\TokenFootprint;
use App\Application\Service\CupEvent\AddCupEvent;
use App\Application\Service\CupEvent\AddCupEventService;
use App\Application\Service\CupEvent\CalculateCupEvent;
use App\Application\Service\CupEvent\CalculateCupEventService;
use App\Application\Service\CupEvent\DisableCupEvent;
use App\Application\Service\CupEvent\DisableCupEventService;
use App\Application\Service\CupEvent\Exception\CupEventNotFound;
use App\Application\Service\CupEvent\Exception\UnableToCalculateCupEvent;
use App\Application\Service\CupEvent\ListCupsEvents;
use App\Application\Service\CupEvent\ListCupsEventsService;
use App\Application\Service\CupEvent\UpdateCupEventAttributes;
use App\Application\Service\CupEvent\UpdateCupEventAttributesService;
use App\Application\Service\CupEvent\ViewCupEvent;
use App\Application\Service\CupEvent\ViewCupEventService;
use App\Bridge\Laravel\Http\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class CupEventController extends Controller
{
    public function view(string $id, ViewCupEventService $service): ViewCupEventDto
    {
        try {
            return $service->execute(new ViewCupEvent($id));
        } catch (CupEventNotFound) {
            throw new NotFoundHttpException();
        }
    }

    public function list(CupEventSearchDto $search, ListCupsEventsService $service): PaginationAdapter
    {
        return $service->execute(new ListCupsEvents($search));
    }

    public function create(
        CupEventDto $cup,
        AddCupEventService $service,
        TokenFootprint $token,
    ): ViewCupEventDto {
        return $service->execute(new AddCupEvent($cup, $token));
    }

    public function changeAttributes(
        string $id,
        CupEventAttributesDto $attributes,
        UpdateCupEventAttributesService $service,
        TokenFootprint $token,
    ): Response {
        try {
            $service->execute(new UpdateCupEventAttributes($id, $attributes, $token));
        } catch (CupEventNotFound) {
            throw new NotFoundHttpException();
        }

        return new Response(status: Response::HTTP_NO_CONTENT);
    }

    /** @return ViewCupEventPointsDto[] */
    public function calculate(
        CalculateCupEventDto $dto,
        CalculateCupEventService $service,
        TokenFootprint $token,
    ): array {
        try {
            return $service->execute(new CalculateCupEvent($dto, $token));
        } catch (CupEventNotFound) {
            throw new NotFoundHttpException();
        } catch (UnableToCalculateCupEvent) {
            throw new ConflictHttpException();
        }
    }

    public function disable(
        string $id,
        DisableCupEventService $service,
        TokenFootprint $token,
    ): Response {
        try {
            $service->execute(new DisableCupEvent($id, $token));
        } catch (CupEventNotFound) {
            throw new NotFoundHttpException();
        }

        return new Response(status: Response::HTTP_NO_CONTENT);
    }
}
