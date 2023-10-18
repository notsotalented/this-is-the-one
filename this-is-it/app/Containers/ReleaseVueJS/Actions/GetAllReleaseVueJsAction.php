<?php

namespace App\Containers\ReleaseVueJS\Actions;

use App\Containers\ReleaseVueJS\Tasks\GetAllReleaseVueJsTask;
use App\Ship\Parents\Actions\Action;
use App\Ship\Transporters\DataTransporter;
use Illuminate\Support\Facades\App;

class GetAllReleaseVueJsAction extends Action
{
    public function run(DataTransporter $request = NULL)
    {
        $instance = App::make(GetAllReleaseVueJsTask::class);
        $instance->addRequestCriteria();
        $instance->ordered();
        $releasevuejs = $instance->run($request->paginate ?? 10);

        return $releasevuejs;
    }
}
