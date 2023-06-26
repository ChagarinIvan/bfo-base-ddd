<?php

declare(strict_types=1);

namespace App\Bridge\Laravel\Http\Controller;

use App\Application\Dto\AbstractDto;
use App\Application\Dto\Shared\PaginationAdapter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

abstract class Controller extends BaseController
{
    public function __construct(private readonly Request $request)
    {
    }

    /**
     * @param string $method
     * @param array<int, mixed> $parameters
     */
    public function callAction($method, $parameters): Response
    {
        $injectParams = [];
        foreach ($parameters as $parameter) {
            if ($parameter instanceof AbstractDto) {
                try {
                    $validated = $this->request->validate($parameter::validationRules());
                } catch (ValidationException $e) {
                    throw new BadRequestHttpException($e->getMessage(), previous: $e);
                }
                $parameter = $parameter->fromArray($validated);
            }
            $injectParams[] = $parameter;
        }

        /** @var Response|mixed $response */
        $response = parent::callAction($method, $injectParams);

        if ($response instanceof PaginationAdapter) {
            $paginateResponse = new JsonResponse($response->items());
            $paginateResponse->header('X-Count', (string) $response->count());
            $paginateResponse->header('X-Page', (string) $response->page());
            $paginateResponse->header('X-Per-Page', (string) $response->perPage());
            $paginateResponse->header('X-Total-Count', (string) $response->total());

            return $paginateResponse;
        }

        return $response instanceof Response ? $response : new JsonResponse($response);
    }
}
