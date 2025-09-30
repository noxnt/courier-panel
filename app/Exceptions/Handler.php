<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Support\ApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $e): Response|ApiResponse
    {
        if ($request->is('api/*')) {
            if ($response = $this->processHttpErrors($e)) {
                return $response;
            }
        }

        return parent::render($request, $e);
    }

    private function processHttpErrors(Throwable $e): Response|ApiResponse|null
    {
        return match(true) {
            $e instanceof AuthenticationException => ApiResponse::error(
                    message: 'Unauthorized access.',
                    status: Response::HTTP_UNAUTHORIZED
                ),

            $e instanceof AuthorizationException => ApiResponse::error(
                message: 'Access denied.',
                status: Response::HTTP_FORBIDDEN
            ),

            $e instanceof ValidationException => ApiResponse::error(
                message: 'Validation error.',
                errors: $e->errors(),
                status: Response::HTTP_UNPROCESSABLE_ENTITY
            ),

            $e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException => ApiResponse::error(
                message: 'Resource not found.',
                status: Response::HTTP_NOT_FOUND
            ),

            $e instanceof ConflictHttpException => ApiResponse::error(
                message: $e->getMessage() ?? 'Conflict.',
                status: Response::HTTP_CONFLICT
            ),

            default => null,
        };
    }
}
