<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\AccountResource;
use App\Models\Api\Zoho;


class AccountController extends Controller
{

    public function getAccounts(Zoho $zoho)
    {
        $accounts = $zoho->getAccounts();

        return new AccountResource(collect($accounts));
    }

    public function storeAccount(Request $request)
    {
        //
    }

}
