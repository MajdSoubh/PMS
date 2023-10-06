<?php

namespace App\Traits;

trait HttpResponses
{
    protected function success($data, $message = null, $code = 200)
    {
        return response()->json(["status" => 1, "data" => $data, "message" => $message], $code);
    }
    protected function error($data, $message = null, $code)
    {
        return response()->json(["status" => 0, "data" => $data, "message" => $message], $code);
    }
}
