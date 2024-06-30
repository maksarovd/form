<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class ZohoApiAbstract extends Model
{
    public function get($url, $headers, $params = null): array
    {
        try{
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            if(is_array($params)){
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
            }
            if(is_string($params)){
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            }

            $response = curl_exec($ch);
        }catch(\Exception $exception){
            throw new \Exception($exception->getMessage());
        }

        if($response === false){
            throw new \Exception('error with ' . curl_error($ch));
        }

        curl_close($ch);

        return (array) json_decode($response, true);
    }

    /**
     * Post
     *
     *
     * @access public
     * @param $url
     * @param $headers
     * @param $params
     * @return array
     * @throws \Exception
     */
    public function post($url, $headers, $params): array
    {
        try{
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);

            if(is_array($params)){
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
            }
            if(is_string($params)){
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            }

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $response = curl_exec($ch);
        }catch(\Exception $exception){
            throw new \Exception($exception->getMessage());
        }

        if($response === false){
            throw new \Exception('error with ' . curl_error($ch));
        }

        curl_close($ch);

        return (array) json_decode($response, true);
    }
}
