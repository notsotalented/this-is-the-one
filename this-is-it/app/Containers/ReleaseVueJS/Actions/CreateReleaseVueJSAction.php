<?php

namespace App\Containers\ReleaseVueJS\Actions;

use App\Containers\ReleaseVueJS\Models\ReleaseVueJS;
use App\Containers\ReleaseVueJS\Tasks\CreateReleaseVueJSTask;
use App\Ship\Parents\Actions\Action;
use App\Ship\Transporters\DataTransporter;
use Illuminate\Support\Facades\App;

class CreateReleaseVueJSAction extends Action
{
    public function run(DataTransporter $data): ReleaseVueJS
    {

        $release = App::make(CreateReleaseVueJSTask::class)->run(
            $data->name,
            $data->title_description,
            $data->detail_description,
            $data->is_publish ?? false,
            $data->images ?? null,
        );

        return $release;
    }
}