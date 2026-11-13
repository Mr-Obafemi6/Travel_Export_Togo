<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('reservations:cloturer')->dailyAt('01:00');
