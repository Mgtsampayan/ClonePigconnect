<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\PigFarmInformation;

class SendWeatherUpdateNotifications extends Command
{
    protected $signature = 'notify:weather-updates';
    protected $description = 'Send email notifications to users about the current weather';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $users = User::all();
        foreach ($users as $user) {
            $farmInfo = PigFarmInformation::where('user_id', $user->id)->first();

            if (!$farmInfo) {
                $this->info("Skipping user {$user->email} because farm information not found");
                continue;
            }

            $apiKey = '58bc032bc09b45d38ac05217241111';
            $lat = $farmInfo->latitude;
            $lon = $farmInfo->longitude;

            try {
                $response = Http::get("http://api.weatherapi.com/v1/current.json?key={$apiKey}&q={$lat},{$lon}");

                if ($response->failed()) {
                    $this->error("Failed to fetch weather data for user {$user->email}");
                    continue;
                }

                $weatherData = $response->json();
                $temperature = $weatherData['current']['temp_c'];
                $weather = $weatherData['current']['condition']['text'];

                Mail::raw("Current weather at your farm: Temperature: {$temperature} °C, Status: {$weather}", function ($message) use ($user) {
                    $message->to($user->email)
                            ->subject('Weather Update Notification');
                });

                $this->info("Weather update notification sent to {$user->email}");
            } catch (\Exception $e) {
                $this->error("An error occurred while fetching weather data for user {$user->email}: {$e->getMessage()}");
            }
        }

        $this->info('Weather update notifications sent successfully.');
    }
}