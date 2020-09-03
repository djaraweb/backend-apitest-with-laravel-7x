<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use App\Traits\ApiResponser;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Validation\ValidationException;


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
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
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
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        // forma de como realizar un test en las exepciones:
        //dd($exception);

        if ($exception instanceof ValidationException){
            return $this->convertValidationExceptionToResponse($exception, $request);
        }

        if ($exception instanceof ModelNotFoundException){
            $modelo = strtolower(class_basename($exception->getModel()));
            return $this->responseToError("No existe ninguna instancia de [{$modelo}] con el Id especificado",404);
        }

        if ($exception instanceof QueryException) {

            $code = $exception->errorInfo[1];
            $message = $exception->errorInfo[2];

            if ($code==1451){
                return $this->responseToError('No se puede eliminar de forma permanente el recurso porque está realacionado con algún otro',409);
            }

            return $this->responseToError("[$code] $message",406);
        }

        if ($exception instanceof NotFoundHttpException) {
            return $this->responseToError("No se encontró la URL especificada",404);
        }

        if ($exception instanceof HttpException) {
            return $this->convertHttpExceptionToResponse($exception, $request);
        }

        if ($exception instanceof AuthenticationException) {
            return $this->responseToError('Usuario no esta autenticado',401);
        }

        if ($exception instanceof AuthorizationException) {
            return $this->responseToError("No posee permisos para acceder a esta acción",403);;
        }

        return parent::render($request, $exception);
    }

        /**
     * Create a response object from the given validation exception.
     *
     * @param  \Illuminate\Validation\ValidationException  $e
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $errors = $e->validator->errors()->getMessages();

        if ($this->isFrontend($request)){
            return $request->ajax() ? response()->json($errors, 422) : redirect()
                    ->back()
                    ->withInput($request->input())
                    ->withErrors($errors);
        }

        return $this->responseToError(['errors'=>$errors],422);
    }

    protected function convertHttpExceptionToResponse(HttpException $e, $request)
    {
        $statuscode = $e->getStatusCode();
        $errors = $e->getMessage();
        return $this->responseToError($errors,$statuscode);
    }

    private function isFrontend($request){
        return $request->acceptsHtml() && collect($request->route()->middleware())->contains('web');
    }

}
