<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    try {
        $holidayCalendar = Cache::remember(
            'frontend.holiday-calendar.v2',
            now()->addMinutes(30),
            function () {
                $holidays = Http::acceptJson()
                    ->timeout(10)
                    ->retry(2, 200)
                    ->get('https://hotmobily.jp/api/get_holidays.php')
                    ->throw()
                    ->json();

                return collect($holidays)
                    ->filter(function ($holiday) {
                        $type = (int) data_get($holiday, 'extendedProps.type');

                        return filled(data_get($holiday, 'start'))
                            && in_array($type, [2, 3], true);
                    })
                    ->mapWithKeys(function ($holiday) {
                        return [
                            data_get($holiday, 'start') =>
                                (int) data_get($holiday, 'extendedProps.type'),
                        ];
                    })
                    ->all();
            }
        );
    } catch (Throwable $exception) {
        report($exception);
        $holidayCalendar = [];
    }

    return view('frontend.home.index', compact('holidayCalendar'));
})->name('home');
