<?php

namespace App\Containers\Includes\UI\WEB\Controllers;

use App\Containers\Includes\UI\WEB\Requests\CreateIncludesRequest;
use App\Containers\Includes\UI\WEB\Requests\DeleteIncludesRequest;
use App\Containers\Includes\UI\WEB\Requests\GetAllIncludesRequest;
use App\Containers\Includes\UI\WEB\Requests\FindIncludesByIdRequest;
use App\Containers\Includes\UI\WEB\Requests\UpdateIncludesRequest;
use App\Containers\Includes\UI\WEB\Requests\StoreIncludesRequest;
use App\Containers\Includes\UI\WEB\Requests\EditIncludesRequest;
use App\Ship\Parents\Controllers\WebController;
use Apiato\Core\Foundation\Facades\Apiato;

/**
 * Class Controller
 *
 * @package App\Containers\Includes\UI\WEB\Controllers
 */
class Controller extends WebController
{
    /**
     * Show all entities
     *
     * @param GetAllIncludesRequest $request
     */
    public function index(GetAllIncludesRequest $request)
    {
        $includes = Apiato::call('Includes@GetAllIncludesAction', [$request]);

        // ..
    }

    /**
     * Show one entity
     *
     * @param FindIncludesByIdRequest $request
     */
    public function show(FindIncludesByIdRequest $request)
    {
        $includes = Apiato::call('Includes@FindIncludesByIdAction', [$request]);

        // ..
    }

    /**
     * Create entity (show UI)
     *
     * @param CreateIncludesRequest $request
     */
    public function create(CreateIncludesRequest $request)
    {
        // ..
    }

    /**
     * Add a new entity
     *
     * @param StoreIncludesRequest $request
     */
    public function store(StoreIncludesRequest $request)
    {
        $includes = Apiato::call('Includes@CreateIncludesAction', [$request]);

        // ..
    }

    /**
     * Edit entity (show UI)
     *
     * @param EditIncludesRequest $request
     */
    public function edit(EditIncludesRequest $request)
    {
        $includes = Apiato::call('Includes@GetIncludesByIdAction', [$request]);

        // ..
    }

    /**
     * Update a given entity
     *
     * @param UpdateIncludesRequest $request
     */
    public function update(UpdateIncludesRequest $request)
    {
        $includes = Apiato::call('Includes@UpdateIncludesAction', [$request]);

        // ..
    }

    /**
     * Delete a given entity
     *
     * @param DeleteIncludesRequest $request
     */
    public function delete(DeleteIncludesRequest $request)
    {
         $result = Apiato::call('Includes@DeleteIncludesAction', [$request]);

         // ..
    }
}
