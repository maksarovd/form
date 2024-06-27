<?php

namespace App\Models\Api;

use Illuminate\Support\Facades\Redis;


class Zoho extends ZohoAbstract
{

    public function getAccounts()
    {
        $accounts = [];

        if(!$this->checkToken()){
            return $accounts;
        }


        $url = "https://www.zohoapis.eu/crm/v2/Accounts";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Zoho-oauthtoken ' . $this->getOrCreateToken()
        ]);

        $result = curl_exec($ch);

        $data = json_decode($result, true);

        if(curl_error($ch) || !empty($data['status']) &&  $data['status'] === 'error'){
            return $accounts;
        }

        $accounts = $data['data'];

        return $accounts;
    }


    public function getStages()
    {

        $deals = [
            'Оценка пригодности',
            'Требуется анализ',
            'Ценностное предложение',
            'Идентификация ответственных за принятие решений',
            'Коммерческое предложение/Ценовое предложение',
            'Переговоры /Оценка',
            'Закрытые заключенные',
            'Закрытые упущенные',
            'Закрытые и выигранные конкурентами',
            'Identify Decision Makers'
        ];

        return $deals;
    }


    public function storeAccount($request)
    {
        $params = [
            'data' => [
                [
                    'Account_Name' => $request->get('Account_Name'),
                    'Website' => $request->get('Website'),
                    'Phone' => $request->get('Phone'),
                    'Billing_City' => $request->get('Billing_City'),
                ]
            ]
        ];


        $url = "https://www.zohoapis.eu/crm/v2/Accounts";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Zoho-oauthtoken ' . $this->getOrCreateToken()
        ]);

        $result = curl_exec($ch);

        $data = json_decode($result, true);

        if(curl_error($ch) || !empty($data['status']) &&  $data['status'] === 'error'){
            return false;
        }

        return true;
    }


    public function storeDeal($request)
    {
        $url = "https://www.zohoapis.eu/crm/v2/Deals";

        $params = [
            'data' => [
                [
                    'Deal_Name' => $request->get('Deal_Name'),
                    'Stage' => $request->get('Stage'),
                    'Amount' => $request->get('Amount'),
                    'Closing_Date' => $request->get('Closing_Date'),
                    'Account_Name' => $request->get('Account_Name')
                ]
            ]
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Zoho-oauthtoken ' . $this->getOrCreateToken()
        ]);

        $result = curl_exec($ch);

        $data = json_decode($result, true);

        if(curl_error($ch) || !empty($data['status']) &&  $data['status'] === 'error'){
            return false;
        }

        return true;
    }


    public function storeToken($request)
    {
        $accessToken  = $request->get('access_token', false);
        $refreshToken = $request->get('refresh_token', false);

        if($accessToken && $refreshToken){
            $redis = Redis::connection();
            $redis->set('zoho_access_token', $request->get('access_token', false));
            $redis->set('zoho_access_token_expire', date('Y-m-d h:i:s', strtotime("+50 minutes")) );
            $redis->set('zoho_refresh_token', $request->get('refresh_token', false));
            return true;
        }
        return false;
    }


    protected function refreshToken($redis)
    {
        $url  = "https://accounts.zoho.eu/oauth/v2/token";

        $params = [
            'refresh_token' => $redis->get('zoho_refresh_token'),
            'client_id' => env('ZOHO_CLIENT_ID'),
            'client_secret' => env('ZOHO_CLIENT_SECRET'),
            'redirect_uri' => env('ZOHO_REDIRECT_URI'),
            'grant_type' => 'refresh_token'
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);

        $response = curl_exec($ch);

        $data = json_decode($response, true);

        if(curl_error($ch) || !empty($data['status']) &&  $data['status'] === 'error'){
            throw new Exception('error when refresh zoho token');
        }

        $redis->set('zoho_access_token', $data['access_token']);
        $redis->set('zoho_access_token_expire', date('Y-m-d h:i:s', strtotime("+50 minutes")) );

        return $redis->get('zoho_access_token');
    }


    public function checkToken()
    {
        return (bool) Redis::connection()->get('zoho_access_token');
    }


    public function getOrCreateToken()
    {
        $redis   = (object) Redis::connection();
        $token   = (string) $redis->get('zoho_access_token', false);

        if(!$token){
            throw new \Exception('zoho token not exist. Pleace add tokens via  http://127.0.0.1/api/v2/zoho/init_token');
        }

        $expired = (bool)   (date('Y-m-d h:i:s') > $redis->get('zoho_access_token_expire'));


        if($expired){
            $token = (string) $this->refreshToken($redis);
        }

        return $token;
    }

}
