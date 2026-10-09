<?php

namespace App\Console\Commands;

use App\Models\SponsorInquiry;
use Illuminate\Console\Command;

class PurgeSponsorInquiries extends Command
{
    protected $signature = 'watch:purge-sponsor-inquiries';

    protected $description = 'Delete expired sponsor enquiries';

    public function handle(): int
    {
        $this->info(SponsorInquiry::where('expires_at', '<=', now())->delete().' expired enquiries deleted.');

        return 0;
    }
}
