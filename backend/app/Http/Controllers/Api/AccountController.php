<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\AccountResource;
use Illuminate\Support\Collection;
use App\Models\Api\Zoho;


class AccountController extends Controller
{

    # return AccountResource::collection(collect($accounts));
    public function getAccounts(Zoho $zoho): Collection
    {
        return collect($zoho->getAccounts());
    }


    public function storeAccount(Request $request, Zoho $zoho): bool
    {
        return $zoho->storeAccount($request);
    }

}
