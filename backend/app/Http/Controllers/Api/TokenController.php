<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Api\Zoho;


class TokenController extends Controller
{

    public function initializeToken(Request $requesst, Zoho $zoho): bool
    {
        return $zoho->storeToken($requesst);
    }


    public function checkToken(Zoho $zoho): bool
    {
        return $zoho->checkToken();
    }

}
