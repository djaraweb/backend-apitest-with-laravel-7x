<?php

namespace App\Traits;

trait ApiResponser
{
	protected function responseToMessageCustom($arrayMsg){
		return response()->json($arrayMsg, 200);
	}

    protected function responseToSuccess($data, $code=200){
		return response()->json(['body'=>$data,
								 'code'=>$code],
								$code);
    }

	protected function responseToError($message, $code=501){
		return response()->json(['message'=>$message,
								 'code'=>$code],
								$code);
    }

}
