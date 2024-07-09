<?php

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Arr;
use Illuminate\Validation\Validator;

if (! function_exists('validationError')) {
    function validationError(Validator $validator): JsonResponse
    {
        return response()->json(
            [
                'error' => $validator->errors(),
                'message' => Arr::join($validator->errors()->all(), "\n"),
            ],
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}

if (! function_exists('apiResponse')) {
    function apiResponse(
        ?string $message = null,
        int $status = Response::HTTP_OK,
        $data = null,
        $resource = null,
        array $meta = [],
        array $validation = []
    ): JsonResponse {
        $additional = Arr::where([
            'message' => $message,
            'meta' => $meta,
            'validation' => $validation,
        ], fn ($value) => filled($value));

        if ($data !== null and $resource !== null) {
            if ($data instanceof Collection or
                $data instanceof \Illuminate\Support\Collection or
                $data instanceof LengthAwarePaginator or
                $data instanceof CursorPaginator or
                $data instanceof Paginator
            ) {
                /** @var JsonResource $resource */
                $jsonResource = ($resource)::collection($data);
            } else {
                $jsonResource = new $resource($data);
            }

            return $jsonResource
                ->additional($additional)
                ->response()
                ->setStatusCode($status);
        }
        $additional += ['data' => $data];

        return response()->json($additional, $status);
    }
}

if (! function_exists('responseOK')) {
    function responseOK(
        ?string $message = null,
        $data = null,
        ?string $resource = null,
        array $meta = [],
        array $validation = []
    ): JsonResponse {
        return apiResponse(
            $message,
            Response::HTTP_OK,
            $data,
            $resource,
            $meta,
            $validation
        );
    }
}

if (! function_exists('responseError')) {
    function responseError(
        ?string $message = null,
        $data = null,
        ?string $resource = null,
        array $meta = []
    ): JsonResponse {
        return apiResponse(
            $message,
            Response::HTTP_UNPROCESSABLE_ENTITY,
            $data,
            $resource,
            $meta
        );
    }
}

if (! function_exists('responseNotFound')) {
    function responseNotFound(
        ?string $message = null
    ): JsonResponse {
        return apiResponse(
            $message ?: trans('message.not_found'),
            Response::HTTP_NOT_FOUND
        );
    }
}
