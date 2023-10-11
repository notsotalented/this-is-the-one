<?php

namespace App\Containers\ReleaseVueJS\Actions;

use App\Ship\Parents\Actions\Action;
use Apiato\Core\Foundation\Facades\Apiato;
use App\Ship\Transporters\DataTransporter;

class GetAllReleaseVueJsAction extends Action
{
    public function run(DataTransporter $request)
    {
        $releasevuejs = Apiato::call('ReleaseVueJS@GetAllReleaseVueJsTask', [], ['addRequestCriteria', 'ordered']);

        return $releasevuejs;
    }
}