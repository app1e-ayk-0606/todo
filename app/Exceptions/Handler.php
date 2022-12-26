<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use \Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use App\Exceptions\ApiNoDataExistException;
use Illuminate\Validation\ValidationException; 

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (\Exception $e, $request) {
            if ($request->is('api/*')) {
                $message = 'サーバーエラー';
                $code = Response::HTTP_INTERNAL_SERVER_ERROR;
                if ($e instanceof ApiNoDataExistException) {
                    $message =  '該当のタスクは見つかりませんでした。';
                    $code = Response::HTTP_NOT_FOUND;
                } elseif ($e instanceof ModelNotFoundException) {
                    $message = 'Record not found';
                    $code =  Response::HTTP_NOT_FOUND;
                } elseif ($e instanceof NotFoundHttpException) {
                    $message = 'Not found';
                    $code =  Response::HTTP_NOT_FOUND;
                } elseif ($e instanceof ValidationException) {
                    $message = implode(',', $e->validator->getMessageBag()->all());
                    $code = Response::HTTP_BAD_REQUEST;
                } elseif ($e instanceof HttpException) {
                    $message = $e->getMessage();
                    $code = $e->getStatusCode();
                }
                return response()->json([
                    'message' => $message,
                ], $code);
            }
        });
    }
}
