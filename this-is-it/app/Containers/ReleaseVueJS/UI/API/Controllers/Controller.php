<?php

namespace App\Containers\ReleaseVueJS\UI\API\Controllers;

use App\Containers\ReleaseVueJS\UI\API\Requests\CreateReleaseVueJSRequest;
use App\Containers\ReleaseVueJS\UI\API\Requests\DeleteReleaseVueJSRequest;
use App\Containers\ReleaseVueJS\UI\API\Requests\GetAllReleaseVueJsRequest;
use App\Containers\ReleaseVueJS\UI\API\Requests\FindReleaseVueJSByIdRequest;
use App\Containers\ReleaseVueJS\UI\API\Requests\UpdateReleaseVueJSRequest;
use App\Containers\ReleaseVueJS\UI\API\Transformers\ReleaseVueJSTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Apiato\Core\Foundation\Facades\Apiato;

use App\Ship\Transporters\DataTransporter;
/**
 * Class Controller
 *
 * @package App\Containers\ReleaseVueJS\UI\API\Controllers
 */
class Controller extends ApiController
{
    /**
     * @param CreateReleaseVueJSRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createReleaseVueJS(CreateReleaseVueJSRequest $request)
    {
        $releasevuejs = Apiato::call('ReleaseVueJS@CreateReleaseVueJSAction', [$request]);

        return $this->created($this->transform($releasevuejs, ReleaseVueJSTransformer::class));
    }

    /**
     * @param FindReleaseVueJSByIdRequest $request
     * @return array
     */
    public function findReleaseVueJSById(FindReleaseVueJSByIdRequest $request)
    {
        $releasevuejs = Apiato::call('ReleaseVueJS@FindReleaseVueJSByIdAction', [new DataTransporter($request)]);

        return $this->transform($releasevuejs, ReleaseVueJSTransformer::class);
    }

    /**
     * @param GetAllReleaseVueJsRequest $request
     * @return array
     */
    public function getAllReleaseVueJs(GetAllReleaseVueJsRequest $request)
    {
        $releasevuejs = Apiato::call('ReleaseVueJS@GetAllReleaseVueJsAction', [new DataTransporter($request)]);

        return $this->transform($releasevuejs, ReleaseVueJSTransformer::class);
    }

    /**
     * @param UpdateReleaseVueJSRequest $request
     * @return array
     */
    public function updateReleaseVueJS(UpdateReleaseVueJSRequest $request)
    {
        $releasevuejs = Apiato::call('ReleaseVueJS@UpdateReleaseVueJSAction', [new DataTransporter($request)]);

        return $this->transform($releasevuejs, ReleaseVueJSTransformer::class);
    }

    /**
     * @param DeleteReleaseVueJSRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteReleaseVueJS(DeleteReleaseVueJSRequest $request)
    {
        Apiato::call('ReleaseVueJS@DeleteReleaseVueJSAction', [new DataTransporter($request)]);

        return $this->noContent();
    }
}
