<?php

namespace App\Containers\ReleaseVueJS\Actions;

use App\Containers\ReleaseVueJS\Tasks\DeleteReleaseVueJSTask;
use App\Ship\Parents\Actions\Action;
use App\Ship\Transporters\DataTransporter;
use Illuminate\Support\Facades\App;

class DeleteReleaseVueJSAction extends Action
{
    public function run(DataTransporter $request)
    {
        return App::make(DeleteReleaseVueJSTask::class)->run($request->id);
    }
}