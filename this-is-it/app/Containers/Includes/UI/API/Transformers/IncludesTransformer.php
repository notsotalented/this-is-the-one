<?php

namespace App\Containers\Includes\UI\API\Transformers;

use App\Containers\Includes\Models\Includes;
use App\Ship\Parents\Transformers\Transformer;

class IncludesTransformer extends Transformer
{
    /**
     * @var  array
     */
    protected $defaultIncludes = [

    ];

    /**
     * @var  array
     */
    protected $availableIncludes = [

    ];

    /**
     * @param Includes $entity
     *
     * @return array
     */
    public function transform(Includes $entity)
    {
        $response = [
            'object' => 'Includes',
            'id' => $entity->getHashedKey(),
            'created_at' => $entity->created_at,
            'updated_at' => $entity->updated_at,

        ];

        $response = $this->ifAdmin([
            'real_id'    => $entity->id,
            // 'deleted_at' => $entity->deleted_at,
        ], $response);

        return $response;
    }
}
