<?php

namespace App\Models\Api;

use Illuminate\Support\Facades\Redis;
use Illuminate\Database\Eloquent\Model;


class ZohoApi extends Model implements ZohoApiConstraints
{

    /**
     * Get Accounts
     *
     *
     * @access public
     * @return array
     * @throws \Exception
     */
    public function getAccounts()
    {
        $url = ZohoApiConstraints::ZOHO_API_ENDPOINT_GET_ACCOUNTS;

        $accounts = [];

        if(!$this->checkToken()){
            return $accounts;
        }


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


    /**
     * Get Stages
     *
     *
     * @access public
     * @return array
     */
    public function getStages()
    {
        #p.s. not founded endpoint for stages..

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


    /**
     * Store Account
     *
     *
     * @access public
     * @param $request
     * @return bool
     * @throws \Exception
     */
    public function storeAccount($request)
    {
        $url = ZohoApiConstraints::ZOHO_API_ENDPOINT_STORE_ACCOUNT;

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


    /**
     * Store Deal
     *
     *
     * @access public
     * @param $request
     * @return bool
     * @throws \Exception
     */
    public function storeDeal($request)
    {
        $url = ZohoApiConstraints::ZOHO_API_ENDPOINT_STORE_DEALS;

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


    /**
     * Store Token
     *
     *
     * @access public
     * @param $request
     * @return bool
     */
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


    /**
     * Refresh Token
     *
     *
     * @access protected
     * @param $redis
     * @return mixed
     * @throws \Exception
     */
    protected function refreshToken($redis)
    {
        $url  = ZohoApiConstraints::ZOHO_API_ENDPOINT_REFRESH_TOKEN;

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
            throw new \Exception('error when refresh zoho token');
        }

        $redis->set('zoho_access_token', $data['access_token']);
        $redis->set('zoho_access_token_expire', date('Y-m-d h:i:s', strtotime("+50 minutes")) );

        return $redis->get('zoho_access_token');
    }


    /**
     * Check Token
     *
     *
     * @access public
     * @return bool
     */
    public function checkToken()
    {
        return (bool) Redis::connection()->get('zoho_access_token');
    }


    /**
     * Delete Token
     *
     *
     * @access public
     * @return bool
     */
    public function deleteToken()
    {
        return (bool) !Redis::connection()->set('zoho_access_token',false);
    }


    /**
     * Get Or Create Token
     *
     *
     * @access public
     * @return string
     * @throws \Exception
     */
    public function getOrCreateToken()
    {
        $redis   = (object) Redis::connection();
        $token   = (string) $redis->get('zoho_access_token', false);

        if(!$token){
            throw new \Exception('zoho token not exist. Pleace add tokens via ' . ZohoApiConstraints::ZOHO_API_ENDPOINT_INIT_TOKEN);
        }

        $expired = (bool)   (date('Y-m-d h:i:s') > $redis->get('zoho_access_token_expire'));


        if($expired){
            $token = (string) $this->refreshToken($redis);
        }

        return $token;
    }


}
