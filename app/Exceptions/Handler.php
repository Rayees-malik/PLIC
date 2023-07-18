<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @return void
     *
     * @throws Exception
     */
    public function report(Throwable $exception)
    {
        // if (app()->bound('honeybadger') && $this->shouldReport($exception)) {
        //     app('honeybadger')->notify($exception, app('request'));
        // }

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof AuthorizationException) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthorized.'], 403);
            }

            return redirect()->route('home');
        }

        return parent::render($request, $exception);
    }

    public function register()
    {
        $this->reportable(function (Throwable $e) {
            if (app()->bound('honeybadger')) {
                app('honeybadger')->context([
                    'user_email' => auth()->user() ? auth()->user()->email : null,
                ]);
                app('honeybadger')->notify($e, app('request'));
            }
        });
    }
}
