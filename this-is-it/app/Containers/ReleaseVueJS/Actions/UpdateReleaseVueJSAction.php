<?php

namespace App\Containers\ReleaseVueJS\Actions;

use App\Ship\Parents\Actions\Action;
use Apiato\Core\Foundation\Facades\Apiato;
use App\Ship\Transporters\DataTransporter;

class UpdateReleaseVueJSAction extends Action
{
    public function run(DataTransporter $request)
    {
        $data = [
            'name'               => $request->name,
            'title_description'  => $request->title_description,
            'detail_description' => $request->detail_description,
            'is_publish'         => $request->is_publish ? true : false,
        ];


        // remove null values and their keys but keep 0 values
        $data = array_filter($data, function ($value) {
            return $value !== null;
        });

        $data = array_merge($data, ['images' => $request->images == [] ? null : $request->images]);


        $releasevuejs = Apiato::call('ReleaseVueJS@UpdateReleaseVueJSTask', [$request->id, $data]);

        return $releasevuejs;
    }
}
