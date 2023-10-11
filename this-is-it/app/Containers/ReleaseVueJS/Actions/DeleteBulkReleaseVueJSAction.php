<?php

namespace App\Containers\ReleaseVueJS\Actions;

use App\Containers\ReleaseVueJS\Tasks\DeleteBulkReleaseVueJSTask;
use App\Ship\Parents\Actions\Action;
use Apiato\Core\Foundation\Facades\Apiato;
use App\Ship\Transporters\DataTransporter;
use Illuminate\Support\Facades\App;

class DeleteBulkReleaseVueJSAction extends Action
{
    public function run(DataTransporter $request)
    {
        return App::make(DeleteBulkReleaseVueJSTask::class)->run($request->id);
    }
}