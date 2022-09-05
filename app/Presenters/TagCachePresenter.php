<?php

namespace App\Presenters;

use App\Transformers\TagCacheTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class TagCachePresenter.
 *
 * @package namespace App\Presenters;
 */
class TagCachePresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new TagCacheTransformer();
    }
}
