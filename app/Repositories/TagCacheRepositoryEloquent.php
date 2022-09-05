<?php

namespace App\Repositories;

use App\Presenters\TagCachePresenter;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\TagCacheRepository;
use App\Entities\TagCache;
use App\Validators\TagCacheValidator;

/**
 * Class TagCacheRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class TagCacheRepositoryEloquent extends AppRepository implements TagCacheRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return TagCache::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return TagCacheValidator::class;
    }

    /**
     * @return string
     */
    public function presenter()
    {
        return TagCachePresenter::class;
    }

}
