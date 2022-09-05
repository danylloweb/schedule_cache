<?php

namespace App\Services;

use App\Criterias\AppRequestCriteria;
use App\Repositories\TagCacheRepository;


/**
 * UserService
 */
class TagCacheService
{
    /**
     * @var TagCacheRepository
     */
    protected $repository;


    /**
     * @param TagCacheRepository $repository
     * @param \App\Services\ClientErgonService $clientService
     */
    public function __construct(TagCacheRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param int $limit
     * @return mixed
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function all($limit = 20):mixed
    {
        return $this->repository
            ->resetCriteria()
            ->pushCriteria(app(AppRequestCriteria::class))
            ->paginate($limit);
    }


}
