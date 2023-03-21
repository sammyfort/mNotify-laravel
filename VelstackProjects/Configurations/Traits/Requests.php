<?php

namespace Velstack\Mnotify\VelstackProjects\Configurations\Traits;

use Illuminate\Support\Facades\Http;

trait Requests
{

    private static function postRequest(string $endpoint, $data=null){

        $response = Http::withHeaders([
            "Content-Type" =>  "application/json", "Accept: application/json", "Cache-Control" => "no-cache"
        ])->post($endpoint, $data);

        $response->throw();
        if ($response['status'] != 'success'){
            return  response()->json(json_decode($response), 400);
        }
        return $response;
    }

    private static function postMediaRequest(string $endpoint, $data=null){
        $response = Http::withHeaders(["Content-Type" =>  "application/json",])->asMultipart()->post($endpoint, $data);
        $response->throw();
        if ($response['status'] != 'success'){
            return  response()->json(json_decode($response), 400);
        }
        return $response;
    }


    private static function getRequest(string $endpoint){
        $response =  Http::withHeaders(["Content-Type" =>  "application/json"])->get($endpoint);
        $response->throw();
        if ($response['status'] != 'success'){
            return  response()->json(json_decode($response), 400);
        }
        return $response;
    }

    private static function putRequest( string $url, array $data){
        $response =  Http::withHeaders(["Content-Type" =>  "application/json"])->put($url, $data);
        $response->throw();
        if ($response['status'] != 'success'){
            return  response()->json(json_decode($response), 400);
        }
        return $response;
    }

    private static function deleteRequest(string $url){
        $response =  Http::withHeaders(["Content-Type" =>  "application/json"])->delete($url);
        $response->throw();
        if ($response['status'] != 'success'){
            return  response()->json(json_decode($response), 400);
        }
        return $response;
    }

}
