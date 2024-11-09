<?php

namespace App\Helpers;


class Helper{

    public static function sendResponse($data,$message='',$code=200){

        return \Response::json([
            'success' => true,
            'data' => $data,
            'message' => $message
        ],$code);
    }

    public static function sendError($message,$code=500){

        return \Response::json([
            'success' => false,
            'message' => $message,
            'status_code' => $code
        ],$code);
    }
}
