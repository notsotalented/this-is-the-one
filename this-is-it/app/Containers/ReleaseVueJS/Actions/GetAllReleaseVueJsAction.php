<?php

namespace App\Containers\ReleaseVueJS\Actions;

use App\Containers\ReleaseVueJS\Tasks\GetAllReleaseVueJsTask;
use App\Ship\Parents\Actions\Action;
use Illuminate\Support\Facades\App;

class GetAllReleaseVueJsAction extends Action
{
    public function run()
    {
        $instance = App::make(GetAllReleaseVueJsTask::class);
        $instance->addRequestCriteria();
        $instance->ordered();
        $releasevuejs = $instance->run();

        return $releasevuejs;
    }
}