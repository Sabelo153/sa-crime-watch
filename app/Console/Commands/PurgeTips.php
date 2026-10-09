<?php

namespace App\Console\Commands;

use App\Models\Tip;
use Illuminate\Console\Command;

class PurgeTips extends Command
{
    protected $signature = 'watch:purge-tips';

    protected $description = 'Delete expired tips';

    public function handle(): int
    {
        $this->info(Tip::where('expires_at', '<=', now())->delete().' expired tips deleted.');

        return 0;
    }
}
