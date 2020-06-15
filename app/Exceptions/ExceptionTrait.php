<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait ExceptionTrait{

    public function apiException($request, $e){

        if($this->isModel($e)){
            return $this->errorResponse('Model not found');
        }else if($this->isHttp($e)){
            return $this->errorResponse('Incorrect route');
        }else if($e instanceof MethodNotAllowedHttpException){
            return $this->errorResponse('Method not Allowed');
        }

        return parent::render($request, $e);

    }

    private function errorResponse($msg){
        return response()->json([
            'success' => false,
            'errors'=> $msg
        ], Response::HTTP_NOT_FOUND);
    }

    private function isModel($e){
        return $e instanceof ModelNotFoundException;
    }

    private function isHttp($e){
        return $e instanceof NotFoundHttpException;
    }
}