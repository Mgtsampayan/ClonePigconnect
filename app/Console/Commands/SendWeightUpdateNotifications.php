<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class SendWeightUpdateNotifications extends Command
{
    protected $signature = 'notify:weight-updates';
    protected $description = 'Send email notifications to users to update pig weights';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $users = User::all();
        foreach ($users as $user) {
            Mail::raw('Please update the weight of your pigs for this month.', function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Pig Weight Update Reminder');
            });
        }

        $this->info('Weight update notifications sent successfully.');
    }
}