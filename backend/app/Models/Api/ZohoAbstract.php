<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use com\zoho\api\authenticator\OAuthToken;
use com\zoho\crm\api\Initializer;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\api\Logger;
use com\zoho\api\Logger\Levels;

use com\zoho\api\logger\LogBuilder;


class ZohoAbstract extends Model
{
    use HasFactory;


    function initialize()
    {
        $logger = (new LogBuilder())
            ->level(Levels::INFO)
            ->filePath(storage_path('logs/zoho.log'))
            ->build();


        $user = new UserSignature('bondspear2020@gmail.com');

        $environment = USDataCenter::PRODUCTION();

        $token = new OAuthToken(
            '1000.KFB8WKBB0E72X7CU5GAMAE33RLG7TG', // Client ID
            '659ffac2581c9b1234c812cb12ae1c2c3a834942de',  // Client Secret
            '1000.1ee10c14fbe506908c78c330513dd11d.0616217364d36d8f90dad8ff92f3c6d4', // Refresh Token
            'https://accounts.zoho.eu/oauth/v2/token'
        );

//        $initializer = new Initializer::initialize($environment,$token)
//                ->user($user)
//                ->environment($environment)
//                ->token($token)
//                ->logger($logger)
//                ->initialize();
    }
}