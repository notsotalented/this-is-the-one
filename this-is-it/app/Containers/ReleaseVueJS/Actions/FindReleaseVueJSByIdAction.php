<?php

namespace App\Containers\ReleaseVueJS\Actions;

use App\Containers\ReleaseVueJS\Tasks\FindReleaseVueJSByIdTask;
use App\Ship\Parents\Actions\Action;
use App\Ship\Transporters\DataTransporter;
use Illuminate\Support\Facades\App;

class FindReleaseVueJSByIdAction extends Action
{
    public function run(DataTransporter $request)
    {
        $releasevuejs = App::make(FindReleaseVueJSByIdTask::class)->run($request->id);

        return $releasevuejs;
    }
}