<?php

namespace App\Http\Controllers;

use App\Services\TagCacheService;
use App\Validators\TagCacheValidator;

/**
 * Class TagCachesController.
 *
 * @package namespace App\Http\Controllers;
 */
class TagCachesController extends Controller
{
    /**
     * @var $service
     */
    protected $service;

    /**
     * @var TagCacheValidator
     */
    protected $validator;

    /**
     * TagCachesController constructor.
     *
     * @param TagCacheService $service
     * @param TagCacheValidator $validator
     */
    public function __construct(TagCacheService $service, TagCacheValidator $validator)
    {
        $this->service = $service;
        $this->validator  = $validator;
    }

}
