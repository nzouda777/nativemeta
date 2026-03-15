<?php

namespace App\Console\Commands;

use App\Models\InvitationToken;
use Illuminate\Console\Command;

class CleanExpiredTokens extends Command
{
    protected $signature = 'nativemeta:tokens-cleanup';
    protected $description = 'Remove expired invitation tokens from the database';

    public function handle()
    {
        $count = InvitationToken::where('expires_at', '<', now())->delete();

        if ($count > 0) {
            $this->info("Deleted {$count} expired invitation tokens.");
        } else {
            $this->info("No expired tokens found.");
        }

        return 0;
    }
}
