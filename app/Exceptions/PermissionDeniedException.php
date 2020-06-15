<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class PermissionDeniedException extends Exception
{
    public function render(){
    	return response([
            'success' => false,
            'errors'=> 'Unauthorized'
        ], Response::HTTP_UNAUTHORIZED);
    }
}
