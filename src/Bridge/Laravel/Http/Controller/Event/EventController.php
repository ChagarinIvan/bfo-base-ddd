<?php

declare(strict_types=1);

namespace App\Bridge\Laravel\Http\Controller\Event;

use App\Application\Dto\Event\EventDto;
use App\Application\Dto\Event\EventInfoDto;
use App\Application\Dto\Event\EventSearchDto;
use App\Application\Dto\Event\ViewEventDto;
use App\Application\Dto\Shared\PaginationAdapter;
use App\Application\Dto\Shared\TokenFootprint;
use App\Application\Service\Event\AddEvent;
use App\Application\Service\Event\AddEventService;
use App\Application\Service\Event\DisableEvent;
use App\Application\Service\Event\DisableEventService;
use App\Application\Service\Event\Exception\EventNotFound;
use App\Application\Service\Event\ListEvents;
use App\Application\Service\Event\ListEventsService;
use App\Application\Service\Event\UpdateEventInfo;
use App\Application\Service\Event\UpdateEventInfoService;
use App\Application\Service\Event\ViewEvent;
use App\Application\Service\Event\ViewEventService;
use App\Bridge\Laravel\Http\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class EventController extends Controller
{
    public function view(string $id, ViewEventService $service): ViewEventDto
    {
        try {
            return $service->execute(new ViewEvent($id));
        } catch (EventNotFound) {
            throw new NotFoundHttpException();
        }
    }

    public function list(EventSearchDto $search, ListEventsService $service): PaginationAdapter
    {
        return $service->execute(new ListEvents($search));
    }

    public function create(
        EventDto $competition,
        AddEventService $service,
        TokenFootprint $token,
    ): ViewEventDto {
        return $service->execute(new AddEvent($competition, $token));
    }

    public function changeEventInfo(
        string $id,
        EventInfoDto $info,
        UpdateEventInfoService $service,
        TokenFootprint $token,
    ): Response {
        try {
            $service->execute(new UpdateEventInfo($id, $info, $token));
        } catch (EventNotFound) {
            throw new NotFoundHttpException();
        }

        return new Response(status: Response::HTTP_NO_CONTENT);
    }

    public function disable(
        string $id,
        DisableEventService $service,
        TokenFootprint $token,
    ): Response {
        try {
            $service->execute(new DisableEvent($id, $token));
        } catch (EventNotFound) {
            throw new NotFoundHttpException();
        }

        return new Response(status: Response::HTTP_NO_CONTENT);
    }
}
