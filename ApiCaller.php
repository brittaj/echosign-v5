<?php

class ApiCaller {
    public static function methods($url,$data,$header = array(),$method=NULL)
    {
        $ch = curl_init();
        if($method == 'PUT')
        {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        else if($method == 'POST')
        {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        else if($method == 'GET')
        {
            curl_setopt($ch, CURLOPT_HTTPGET, 1);
        }
        else
        {
            throw new CHttpException('- Invalid Request Method.');
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        $responseCode = curl_getinfo ($ch,CURLINFO_HTTP_CODE);
        $response_info = curl_getinfo($ch);
        curl_close($ch);

        if($responseCode >= 400)
        {
            /*
            echo "<br><br>";
            var_dump($data);
            echo "<br><br>";

            echo "<br><br>".print_r($response_info)."<br><br>";
            echo "<br><br>".$responseCode."<br><br>";
            echo "<br><br>".$response."<br><br>";
            die();
            */

            //throw new CHttpException($responseCode,json_decode($response)->message);
            return $response;
        }
        else
        {
            //echo "<br><br>response : ".$response."<br><br>";
            return $response;
        }
    }
} 
