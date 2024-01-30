<?php

if(!function_exists('response_success')){

    function response_success($data, $additional= []){

        $response = [
            'status' => true,
            'message' => 'OK',
            'data' => $data
        ];
        $result = array_merge($response,$additional);
        return response()
        ->json($result,200);
    }
}   

if(!function_exists('response_create')){

    function response_create($data, $additional = [], $message = null){

        $response = [
            'status' => true,
            'message' => $message ? $message : 'Recurso creado correctamente.',
            'data' => $data
        ];
        $result = array_merge($response,$additional);
        return response()
        ->json($result,201);
    }
}   

if(!function_exists('response_update')){

    function response_update($data, $additional = []){

        $response = [
            'status' => true,
            'message' => 'Recurso actualizado correctamente',
            'data' => $data
        ];
        $result = array_merge($response,$additional);
        return response()
        ->json($result,200);
    }
}

if(!function_exists('response_delete')){

    function response_delete($data, $additional = []){
        
        $response = [
            'status' => true,
            'message' => 'Recurso actualizado correctamente',
            'data' => $data
        ];
        $result = array_merge($response,$additional);
        return response()
        ->json($result,200);
    }
}

if(!function_exists('response_error')){

    function response_error($messages, $code =404, $additional = []){
        $response = [
            'status' => false,
            'message' => $messages,
        ];
        $result = array_merge($response,$additional);
        return response()
        ->json($result,$code);
    }
}