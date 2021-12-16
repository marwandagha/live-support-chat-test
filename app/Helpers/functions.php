<?php

use Illuminate\Support\Facades\Config;

function notFoundResponse()
{
    $response['success'] = false;
    return response()->json($response, 404);
}

function generalError()
{
    $response['success'] = false;
    $response['code'] = Config::get('constants.errorCodes.general_error');
    return response()->json($response, 400);
}

function successResponse($data = null, $token = null)
{
    $response['success'] = true;

    if ($token != null) {
        $response['token'] = $token;
    }

    if ($data != null) {
        $response['data'] = $data;
    }

    return response()->json($response, 200);
}

function errorResponse($code = null, $error = null, $responseStatus = null, $data = null)
{
    $response['success'] = false;
    if ($code != null) {
        $response['code'] = $code;
    }
    if ($error != null) {
        $response['error'] = $error;
    }
    if ($data != null) {
        $response['data'] = $data;
    }
    if ($responseStatus == null) {
        $responseStatus = 400;
    }
    return response()->json($response, $responseStatus);

}






