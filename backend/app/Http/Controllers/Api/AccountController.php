<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\AccountValidateRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use App\Models\Api\ZohoApi;


class AccountController extends Controller
{

    /**
     * Get Accounts
     *
     *
     * @access public
     * @param ZohoApi $zohoApi
     * @return Collection
     * @throws \Exception
     */
    public function getAccounts(ZohoApi $zohoApi): Collection
    {
        return collect($zohoApi->getAccounts());
    }


    /**
     * Store Account
     *
     *
     * @access public
     * @param AccountValidateRequest $request
     * @param ZohoApi $zohoApi
     * @return bool
     * @throws \Exception
     */
    public function storeAccount(AccountValidateRequest $request, ZohoApi $zohoApi): bool
    {
        return $zohoApi->storeAccount($request);
    }


}
