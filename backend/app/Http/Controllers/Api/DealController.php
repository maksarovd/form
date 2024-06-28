<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\DealValidateRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use App\Models\Api\ZohoApi;


class DealController extends Controller
{


    /**
     * Get Stages
     *
     *
     * @access public
     * @param ZohoApi $zohoApi
     * @return Collection
     */
    public function getStages(ZohoApi $zohoApi): Collection
    {
        return collect($zohoApi->getStages());
    }


    /**
     * Store Deal
     *
     *
     * @access public
     * @param DealValidateRequest $request
     * @param ZohoApi $zohoApi
     * @return bool
     * @throws \Exception
     */
    public function storeDeal(DealValidateRequest $request, ZohoApi $zohoApi): bool
    {
        return $zohoApi->storeDeal($request);
    }


}
