<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use App\Models\Api\Zoho;


class DealController extends Controller
{

    #return new DealStageResource(collect($stages));
    public function getStages(Zoho $zoho): Collection
    {
        return collect($zoho->getStages());
    }


    public function storeDeal(Request $request, Zoho $zoho): bool
    {
        return $zoho->storeDeal($request);
    }

}
