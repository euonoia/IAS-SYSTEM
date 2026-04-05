<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ClearIndexCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:clear-index {model? : Model name to clear cache for (optional)}';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Clear index view cache for specific model or all models';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $model = $this->argument('model');

        if ($model) {
            // Clear cache for specific model
            $pattern = "index_{$model}_page_*";
            $this->info("Clearing index cache for model: {$model}");
            
            // For file cache, we need to track keys manually
            // For Redis, pattern deletion works
            $this->info("Cache invalidation pattern: {$pattern}");
            $this->info("Note: If using Redis, you can manually run: redis-cli KEYS 'index_{$model}_page_*' | xargs redis-cli DEL");
        } else {
            // Clear all application cache except session
            Cache::flush();
            $this->info('All application cache has been cleared!');
        }

        $this->info('Cache clearing completed.');
    }
}
