<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\TransaksiPremium;
use Illuminate\Support\Carbon;

class ResetPremiumStatus extends Command
{
    protected $signature = 'premium:reset-expired';
    protected $description = 'Reset status user ke member jika premium expired';

    public function handle()
    {
        $today = Carbon::now();
        $expired = TransaksiPremium::where('tanggal_berakhir', '<', $today)->get();

        foreach ($expired as $transaksi) {
            $user = $transaksi->user;
            if ($user && $user->status === 'premium') {
                $user->status = 'member';
                $user->save();
                $this->info("Status user {$user->username} dikembalikan ke member.");
            }
        }

        return 0;
    }
}
