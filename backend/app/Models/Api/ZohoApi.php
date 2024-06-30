<?php

namespace App\Models\Api;

use Illuminate\Support\Facades\Redis;

class ZohoApi extends ZohoApiAbstract implements ZohoApiConstraints
{

    /**
     * Get Accounts
     *
     *
     * @access public
     * @return array
     * @throws \Exception
     */
    public function getAccounts(): array
    {
        $url = (string) ZohoApiConstraints::ZOHO_API_ENDPOINT_GET_ACCOUNTS;

        $headers = [
            'Content-Type: application/json',
            'Authorization: Zoho-oauthtoken ' . $this->getOrRefreshToken()
        ];

        $response = $this->get($url, $headers, null);

        return (array) $response['data'];
    }


    /**
     * Get Stages
     *
     *
     * @access public
     * @return array
     */
    public function getStages(): array
    {
        #p.s. not founded endpoint for stages..

        return (array) [
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
    public function storeAccount($request): bool
    {
        $url = (string) ZohoApiConstraints::ZOHO_API_ENDPOINT_STORE_ACCOUNT;

        $headers = (array) [
            'Content-Type: application/json',
            'Authorization: Zoho-oauthtoken ' . $this->getOrRefreshToken()
        ];

        $params = (string) json_encode([
            'data' => [
                [
                    'Account_Name' => (string) $request->get('Account_Name'),
                    'Website'      => (string) $request->get('Website'),
                    'Phone'        => (string) $request->get('Phone'),
                    'Billing_City' => (string) $request->get('Billing_City'),
                ]
            ]
        ]);

        $response = $this->post($url, $headers, $params);

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
    public function storeDeal($request): bool
    {
        $url = (string) ZohoApiConstraints::ZOHO_API_ENDPOINT_STORE_DEALS;

        $headers = (array) [
            'Content-Type: application/json',
            'Authorization: Zoho-oauthtoken ' . $this->getOrRefreshToken()
        ];

        $params = (string) json_encode([
            'data' => [
                [
                    'Deal_Name'    => (string) $request->get('Deal_Name'),
                    'Stage'        => (string) $request->get('Stage'),
                    'Amount'       => (string) $request->get('Amount'),
                    'Closing_Date' => (string) $request->get('Closing_Date'),
                    'Account_Name' => (array)  $request->get('Account_Name')
                ]
            ]
        ]);

        $response = $this->post($url, $headers, $params);

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
    public function storeToken($request): bool
    {
        $url = (string) ZohoApiConstraints::ZOHO_API_ENDPOINT_CREATE_TOKEN;

        $headers = (array) ['Content-Type: application/x-www-form-urlencoded'];

        $params = (array) [
            'client_id'     => (string) $request->get('client_id'),
            'client_secret' => (string) $request->get('client_secret'),
            'code'          => (string) $request->get('code'),
            'redirect_uri'  => (string) $request->get('redirect_uri'),
            'grant_type'    => (string) $request->get('grant_type'),
        ];

        $response = $this->post($url, $headers, $params);

        $redis = (object) Redis::connection();
        $redis->set('zoho_access_token', $response['access_token']);
        $redis->set('zoho_refresh_token', $response['refresh_token']);
        $redis->set('zoho_client_id', $request->get('client_id'));
        $redis->set('zoho_client_secret', $request->get('client_secret'));
        $redis->set('zoho_redirect_uri', $request->get('redirect_uri'));
        $redis->set('zoho_access_token_expire', date('Y-m-d h:i:s', strtotime("+50 minutes")) );

        return true;
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
    protected function refreshToken($redis): mixed
    {
        $url = (string) ZohoApiConstraints::ZOHO_API_ENDPOINT_REFRESH_TOKEN;

        $headers = (array) ['Content-Type: application/x-www-form-urlencoded'];

        $params = (array) [
            'refresh_token' => (string) $redis->get('zoho_refresh_token'),
            'client_id'     => (string) $redis->get('zoho_client_id'),
            'client_secret' => (string) $redis->get('zoho_client_secret'),
            'redirect_uri'  => (string) $redis->get('zoho_redirect_uri'),
            'grant_type'    => (string) 'refresh_token'
        ];

        $response = $this->post($url, $headers, $params);

        $redis->set('zoho_access_token', $response['access_token']);
        $redis->set('zoho_access_token_expire', date('Y-m-d h:i:s', strtotime("+50 minutes")) );

        return (string) $redis->get('zoho_access_token');
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
    public function deleteToken(): bool
    {
        return (bool) !Redis::connection()->set('zoho_access_token',false);
    }


    /**
     * Get Or Refresh Token
     *
     *
     * @access protected
     * @return string
     * @throws \Exception
     */
    protected function getOrRefreshToken(): string
    {
        $redis   = (object) Redis::connection();
        $token   = (string) $redis->get('zoho_access_token', false);
        $expired = (bool)   (date('Y-m-d h:i:s') > $redis->get('zoho_access_token_expire'));

        if($expired){
            $token = (string) $this->refreshToken($redis);
        }

        return (string) $token;
    }


}
