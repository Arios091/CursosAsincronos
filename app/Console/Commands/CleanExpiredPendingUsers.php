<?php

namespace App\Console\Commands;

use App\Models\PendingUser;
use Illuminate\Console\Command;

class CleanExpiredPendingUsers extends Command
{
    protected $signature = 'pending-users:clean';
    protected $description = 'Elimina usuarios pendientes con token expirado';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $deleted = PendingUser::where('expires_at', '<', now())->delete();
        $this->info("Se eliminaron {$deleted} registros pendientes expirados.");
        return 0;
    }
}
