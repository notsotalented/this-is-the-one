<?php

namespace App\Containers\ReleaseVueJS\Actions;

use App\Containers\ReleaseVueJS\Models\ReleaseVueJS;
use App\Ship\Parents\Actions\Action;
use Apiato\Core\Foundation\Facades\Apiato;
use App\Ship\Transporters\DataTransporter;

class CreateReleaseVueJSAction extends Action
{
    public function run(DataTransporter $data): ReleaseVueJS
    {
        // $data = $request->sanitizeInput([
        //     // add your request data here
        // ]);

        // $releasevuejs = Apiato::call('ReleaseVueJS@CreateReleaseVueJSTask', [$data]);

        // return $releasevuejs; 
        $release = Apiato::call('ReleaseVueJS@CreateReleaseVueJSTask', [
            $data->name,
            $data->title_description,
            $data->detail_description,
            $data->is_publish ?? false,
            $data->images ?? null,
        ]);

        return $release;
    }
}