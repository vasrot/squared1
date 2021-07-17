<?php

namespace App\Exceptions;

use Throwable;
use App\Traits\ApiResponser;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    use ApiResponser;
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
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        if($exception instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($exception, $request);
        }
        if($exception instanceof ModelNotFoundException) {
            $model = strtolower(class_basename($exception->getModel()));
            return $this->errorResponse("No instance of {$model} with that id.", 404);
        }
        if ($exception instanceof AuthenticationException) {
            return $this->unauthenticated($request, $exception);
        }
        if ($exception instanceof AuthorizationException) {
            return $this->errorResponse('You can\'t run this action.' , 403);
        }
        if ($exception instanceof NotFoundHttpException) {
            return $this->errorResponse('Not found.', 404);
        }
        if ($exception instanceof MethodNotAllowedHttpException) {
            return $this->errorResponse('You can not run this method on this URL', 405);
        }
        if ($exception instanceof HttpException) {
            return $this->errorResponse($exception->getMessage(), $exception->getStatusCode());
        }
        if ($exception instanceof QueryException) {
            $codigo = $exception->errorInfo[1];
            if ($codigo == 1451) {
                return $this->errorResponse('Can not delete.', 409);
            }
        }
        if ($exception instanceof TokenMismatchException) {
            return redirect()->back()->withInput($request->input());
        }

        if (config('app.debug')) {
            return parent::render($request, $exception);
        }
        return $this->errorResponse('Whoops! Some error happened at our end. ', 500);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $this->errorResponse('Unauthenticated.', 401);
    }

    protected function convertValidationExceptionToResponse(ValidationException $exception, $request)
    {
        $errors = $exception->validator->errors()->getMessages();
        return $this->errorResponse($errors, 422);
    }

}
