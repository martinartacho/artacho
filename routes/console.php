<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');



Schedule::command('notifications:send-pending-push')->everyMinute();
Schedule::command('logs:clean-push')->dailyAt('02:00');
Schedule::command('events:generate --days=30')->dailyAt('00:10');


