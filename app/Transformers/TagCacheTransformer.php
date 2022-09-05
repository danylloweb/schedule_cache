<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\TagCache;

/**
 * Class TagCacheTransformer.
 *
 * @package namespace App\Transformers;
 */
class TagCacheTransformer extends TransformerAbstract
{
    /**
     * Transform the TagCache entity.
     *
     * @param \App\Entities\TagCache $model
     *
     * @return array
     */
    public function transform(TagCache $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
