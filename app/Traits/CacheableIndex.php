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
        // Check if the current cache store supports tagging
        $cacheDriver = config('cache.default');
        $tagSupportedDrivers = ['redis', 'memcached', 'dynamodb'];
        
        if (in_array($cacheDriver, $tagSupportedDrivers)) {
            // Cache store supports tags
            Cache::tags([$this->getCacheTag($modelClass)])->flush();
        } else {
            // Cache store doesn't support tags (like file cache)
            // For file cache, we can't selectively clear cache, but we can clear all cache
            // However, this is too aggressive, so we'll skip cache invalidation for file cache
            // The cache will naturally expire based on the configured lifetime
        }
    }
}

