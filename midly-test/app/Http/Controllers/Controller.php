<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {

    }

    public function createSuccessResponse($status, $data, $message, $code = 200, $total_count = 0)
    {
        return response()->json(['success' => $status, 'message' => $message, 'total_count' => $total_count, 'data' => $data], $code);
    }

    public function createErrorMessage($message, $code, $err = null)
    {
        if(isset($err)){
            report($err);

            if(isset($err->validator) && !empty($err->validator)) {
                $message = $err->validator;
            }
        }

        if(is_string($message)){
            $message = rtrim($message, '.');
        }

        return response()->json(['success' => false, 'message' => $message], $code);
    }
}
