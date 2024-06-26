<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\DealStageResource;


class DealController extends Controller
{

    public function getDealStages()
    {
        $stages= [];

        return new DealStageResource(collect($stages));
    }

    public function storeDeal(Request $request)
    {
        //
    }

}
