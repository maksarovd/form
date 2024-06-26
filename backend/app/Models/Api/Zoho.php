<?php

namespace App\Models\Api;

use com\zoho\crm\api\Initializer;
use com\zoho\crm\api\exception\SDKException;
use com\zoho\crm\api\record\RecordsOperations;
use com\zoho\crm\api\record\ParameterMap;
use com\zoho\crm\api\record\GetRecordsParam;
use com\zoho\crm\api\util\Choice;


//Console      https://api-console.zoho.eu/
//
//Error Report https://www.zoho.com/crm/developer/docs/api/v2/access-refresh.html
//
//Docs  https://www.zoho.com/sites/zweb/images/crm/php-sdk-version-3.x.x.pdf
//
//Applications
//
//	Name            Laravelv11
//	Client ID       1000.KFB8WKBB0E72X7CU5GAMAE33RLG7TG
//	Client Secret   659ffac2581c9b1234c812cb12ae1c2c3a834942de
//	Homepage URL    localhost
//	Redirect URIs   http://127.0.0.1/
//        Auth Code       1000.553e24b1b4dd425a81214598aa2a7106.c9cc7a43209904b5c1ecc48ccfc4789f&location
//
//	API Steps
//
//	1)  https://accounts.zoho.eu/oauth/v2/auth?response_type=code&client_id=<client_id>&scope=ZohoCRM.modules.ALL&redirect_uri=http://127.0.0.1/&access_type=offline&prompt=consent
//
//
//
//	2)  https://accounts.zoho.eu/oauth/v2/token?client_id=<client_id>&client_secret=<client_secret>&grant_type=authorization_code&code=<authorization_code>&redirect_uri=<redirect_uri>
//
//
//	{
//        "access_token": "1000.b306af187f077e0e9ec803ebdcee0c1a.27fdc42c1ab4d191e7de7ff8cfe89603",
//	    "refresh_token": "1000.1ee10c14fbe506908c78c330513dd11d.0616217364d36d8f90dad8ff92f3c6d4",
//	    "scope": "ZohoCRM.modules.ALL",
//	    "api_domain": "https://www.zohoapis.eu",
//	    "token_type": "Bearer",
//	    "expires_in": 3600
//	}
//
//
//

//Api Requests
//
//
//
//      Create Account [POST]
//
//      1) https://www.zohoapis.eu/crm/v2/Accounts
//
//      Authorization: Zoho-oauthtoken <ваш_токен_авторизации>
//      Content-Type: application/json
//
//      {
//          "data": [
//	{
//        "Account_Name": "Новая компания",
//	    "Website": "https://newcompany.example.com",
//	    "Phone": "1234567890",
//	    "Billing_City": "Украина"
//	}
//	]
//    }
//
//    Account List [GET]
//
//    Authorization: Zoho-oauthtoken <ваш_токен_авторизации>
//    Content-Type: application/json
//
//    1) https://www.zohoapis.eu/crm/v2/Accounts
//
//
//
//
//
//
//
//
//
//      Create Deal [POST]
//
//      1) https://www.zohoapis.eu/crm/v2/Deals
//
//      Authorization: Zoho-oauthtoken <ваш_токен_авторизации>
//      Content-Type: application/json
//
//
//      {
//          "data": [
//		{
//            "Deal_Name": "Пример сделки",
//		    "Stage": "Qualification",
//		    "Amount": 10000,
//		    "Closing_Date": "2024-07-01",
//		    "Account_Name": {
//            "name": "Имя компании",
//		        "id": "725826000000430008"
//		    }
//		}
//	    ]
//    }
//


class Zoho extends ZohoAbstract
{

    public function getAccounts()
    {
        try {
            // Инициализация SDK
            $this->initialize();

            // Создание экземпляра RecordsOperations
            $recordsOperations = new RecordsOperations();

            // Создание параметров запроса
            $paramInstance = new ParameterMap();
            $paramInstance->add(GetRecordsParam::module(), new Choice("Accounts"));

            // Получение записей
            $response = $recordsOperations->getRecords($paramInstance);

            if ($response != null) {
                // Получение статуса ответа
                echo("Status code: " . $response->getStatusCode() . "\n");

                // Получение списка записей
                $records = $response->getObject();

                if ($records != null) {
                    foreach ($records as $record) {
                        // Получение значения полей записи
                        foreach ($record->getKeyValues() as $key => $value) {
                            echo($key . ": " . $value . "\n");
                        }
                    }
                }
            }
        } catch (SDKException $e) {
            echo("Exception: " . $e->getMessage());
        }

        return $records;
    }

}
