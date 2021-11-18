<?php

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\Validator;

if (!function_exists('validationError')) {
    function validationError(Validator $validator): JsonResponse
    {
        return response()->json(
            [
                'error' => $validator->errors(),
                'message' => $validator->errors()->all(),
            ],
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}

if (!function_exists('apiResponse')) {
    function apiResponse(
        ?string $message = null,
        int $status = Response::HTTP_OK,
        $data = null,
        $jsonResourceClassName = null,
        array $metaData = [],
        array $validation = []
    ): JsonResponse {
        $metaData += [
            'message' => $message == null ? [] : [$message],
        ];
        if ($data !== null and $jsonResourceClassName !== null) {
            if ($data instanceof Collection or
                $data instanceof \Illuminate\Support\Collection or
                $data instanceof LengthAwarePaginator
            ) {
                /** @var JsonResource $jsonResource */
                $jsonResource = ($jsonResourceClassName)::collection($data);
            } else {
                $jsonResource = new $jsonResourceClassName($data);
            }
            $jsonResource->additional($metaData + ['validation' => $validation]);
            return $jsonResource->response()->setStatusCode($status);
        }
        $metaData += ['validation' => $validation];
        $metaData += ['data' => $data];

        return response()->json($metaData, $status);
    }
}

if (!function_exists('responseOK')) {
    function responseOK(
        ?string $message = null,
        $data = null,
        ?string $jsonResourceClassName = null,
        array $metaData = [],
        array $validation = []
    ): JsonResponse {
        return apiResponse($message, Response::HTTP_OK, $data, $jsonResourceClassName, $metaData, $validation);
    }
}

if (!function_exists('responseError')) {
    function responseError(
        ?string $message = null,
        $data = null,
        ?string $jsonResourceClassName = null,
        array $metaData = []
    ): JsonResponse {
        return apiResponse($message, Response::HTTP_UNPROCESSABLE_ENTITY, $data, $jsonResourceClassName, $metaData);
    }
}

if (!function_exists('responseNotFound')) {
    function responseNotFound(
        ?string $message = null
    ): JsonResponse {
        return apiResponse(
            $message ?: trans('not_found'),
            Response::HTTP_NOT_FOUND,
            null,
            null,
            []
        );
    }
}
