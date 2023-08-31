<?php

namespace App\Containers\Includes\UI\API\Controllers;

use App\Containers\Includes\UI\API\Requests\CreateIncludesRequest;
use App\Containers\Includes\UI\API\Requests\DeleteIncludesRequest;
use App\Containers\Includes\UI\API\Requests\GetAllIncludesRequest;
use App\Containers\Includes\UI\API\Requests\FindIncludesByIdRequest;
use App\Containers\Includes\UI\API\Requests\UpdateIncludesRequest;
use App\Containers\Includes\UI\API\Transformers\IncludesTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Apiato\Core\Foundation\Facades\Apiato;

/**
 * Class Controller
 *
 * @package App\Containers\Includes\UI\API\Controllers
 */
class Controller extends ApiController
{
    /**
     * @param CreateIncludesRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createIncludes(CreateIncludesRequest $request)
    {
        $includes = Apiato::call('Includes@CreateIncludesAction', [$request]);

        return $this->created($this->transform($includes, IncludesTransformer::class));
    }

    /**
     * @param FindIncludesByIdRequest $request
     * @return array
     */
    public function findIncludesById(FindIncludesByIdRequest $request)
    {
        $includes = Apiato::call('Includes@FindIncludesByIdAction', [$request]);

        return $this->transform($includes, IncludesTransformer::class);
    }

    /**
     * @param GetAllIncludesRequest $request
     * @return array
     */
    public function getAllIncludes(GetAllIncludesRequest $request)
    {
        $includes = Apiato::call('Includes@GetAllIncludesAction', [$request]);

        return $this->transform($includes, IncludesTransformer::class);
    }

    /**
     * @param UpdateIncludesRequest $request
     * @return array
     */
    public function updateIncludes(UpdateIncludesRequest $request)
    {
        $includes = Apiato::call('Includes@UpdateIncludesAction', [$request]);

        return $this->transform($includes, IncludesTransformer::class);
    }

    /**
     * @param DeleteIncludesRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteIncludes(DeleteIncludesRequest $request)
    {
        Apiato::call('Includes@DeleteIncludesAction', [$request]);

        return $this->noContent();
    }
}
