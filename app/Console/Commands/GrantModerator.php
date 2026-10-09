<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class GrantModerator extends Command
{
    protected $signature = 'watch:moderator {email}';

    protected $description = 'Grant moderator access to an existing registered user';

    public function handle(): int
    {
        $u = User::where('email', $this->argument('email'))->first();
        if (! $u) {
            $this->error('Register that account first.');

            return 1;
        } $u->is_moderator = true;
        $u->save();
        $this->info('Moderator access granted.');

        return 0;
    }
}
