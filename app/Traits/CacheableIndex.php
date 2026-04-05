<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;


trait CacheableIndex
{
    /**
     * Generate a simple cache tag for model queries
     * 
     * @param string $modelClass
     * @return string
     */
    protected function getCacheTag(string $modelClass): string
    {
        return 'index_' . strtolower(class_basename($modelClass));
    }

    /**
     * Forget all index cache for a model (invalidate on data changes)
     * 
     * @param string $modelClass
     * @return void
     */
    protected function forgetAllIndexCache(string $modelClass): void
    {
        // This is called after create/update/delete to invalidate related caches
        // Laravel's view caching and HTTP cache headers will handle the rest
        Cache::tags([$this->getCacheTag($modelClass)])->flush();
    }
}

