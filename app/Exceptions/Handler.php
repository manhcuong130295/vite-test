<?php

namespace App\Exceptions;

use App\Events\HasExceptionEvent;
use Error;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use ParseError;
use Stripe\Exception\InvalidRequestException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof Error ||
            $exception instanceof ParseError ||
            ($exception instanceof HttpException &&
                !($exception instanceof NotFoundHttpException || $exception instanceof MethodNotAllowedHttpException)) ||
            $exception instanceof TooManyRequestsHttpException ||
            $exception instanceof InvalidArgumentException ||
            $exception instanceof ServiceUnavailableHttpException ||
            $exception instanceof InvalidRequestException ||
            $exception instanceof ClientException
        ) {
            try {
                HasExceptionEvent::dispatch($exception);
            } catch (\Throwable $th) {
                Log::info('===SLACK NOTIFICATION ERROR===' . $th->getMessage());
            }
        }

        return parent::render($request, $exception);
    }
}
