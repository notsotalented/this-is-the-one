<?php

namespace App\Containers\ReleaseVueJS\UI\API\Transformers;

use App\Containers\ReleaseVueJS\Models\ReleaseVueJS;
use App\Ship\Parents\Transformers\Transformer;

class ReleaseVueJSTransformer extends Transformer
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
     * @param ReleaseVueJS $entity
     *
     * @return array
     */
    public function transform(ReleaseVueJS $entity)
    {
        $response = [
            'object' => 'ReleaseVueJS',
            'id' => $entity->getHashedKey(),
            'name' => $entity->name,
            'title_description' => $entity->title_description,
            'detail_description' => $entity->detail_description,
            'is_publish' => $entity->is_publish,
            'images' => $entity->images,
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
