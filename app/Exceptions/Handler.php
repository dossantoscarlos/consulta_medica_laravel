<?php

namespace App\Exceptions;

use Exception;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\CssSelector\Exception\InternalErrorException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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

        $this->renderable(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json(['err' => "Not Found." ], $e->getStatusCode());
            }
        });

        $this->renderable(function (AccessDeniedHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json(['err' => $e->getMessage()], $e->getStatusCode());
            }
        });

        $this->renderable(function (ServerException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json(['err' => $e->getMessage()], $e->getCode());
            }
        });

        $this->renderable(function (QueryException $e, Request $request) {
            if ($request->is('api/*')) {
                Log::error("{$e->getMessage()} :: {$e->getCode()}");
                return response()->json(['err' => $e->getMessage()], 400);
            }
        });
    }
}
