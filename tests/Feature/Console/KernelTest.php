<?php

use Carbon\CarbonImmutable;
use Cron\CronExpression;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule;

beforeEach(function () {
    $this->schedule = app()->make(Schedule::class);
});

it('schedules the as400 migrate command at most once', function () {
    $events = collect($this->schedule->events())
        ->filter(fn (Event $event) => stripos($event->command, 'as400:migrate'));

    expect($events->count())->toBe(1);
});

it('as400 migrate command only runs in production', function () {
    $kyolic = collect($this->schedule->events())
        ->filter(fn (Event $event) => stripos($event->command, 'as400:migrate'))->sole();

    expect($kyolic->environments)->toBe(['production']);
});

it('kyolic update command only runs in production', function () {
    $kyolic = collect($this->schedule->events())
        ->filter(fn (Event $event) => stripos($event->command, 'update:kyolic'))->sole();

    expect($kyolic->environments)->toBe(['production']);
});

it('schedules the kylic update command at most once', function () {
    $events = collect($this->schedule->events())
        ->filter(fn (Event $event) => stripos($event->command, 'update:kyolic'));

    expect($events->count())->toBe(1);
});

it('runs the kyolic update command on Sundays after the as400 migration', function () {
    $now = CarbonImmutable::parse('2022-03-06'); // First Sunday in March 2022

    $events = collect($this->schedule->events())
        ->filter(
            function (Event $event) {
                return stripos($event->command, 'as400:migrate') || stripos($event->command, 'update:kyolic');
            }
        );

    expect($events->count())->toBe(2);

    $as400CronExpression = new CronExpression($events->filter(fn (Event $event) => stripos($event->command, 'as400:migrate'))->sole()->expression);
    $kyolicCronExpression = new CronExpression($events->filter(fn (Event $event) => stripos($event->command, 'update:kyolic'))->sole()->expression);

    expect($kyolicCronExpression->getParts()[4])->toBe('0'); // Sunday
    expect($kyolicCronExpression->getNextRunDate($now))->toBeGreaterThan($as400CronExpression->getNextRunDate($now));
});

it('schedules the daily cleanup command at most once', function () {
    $events = collect($this->schedule->events())
        ->filter(fn (Event $event) => stripos($event->command, 'cleanup:daily'));

    expect($events->count())->toBe(1);
});

it('only runs daily cleanup command in production and development', function () {
    $dailyCleanup = collect($this->schedule->events())
        ->filter(fn (Event $event) => stripos($event->command, 'cleanup:daily'))->sole();

    expect($dailyCleanup->environments)->toContain('development');
    expect($dailyCleanup->environments)->toContain('production');
    expect(count($dailyCleanup->environments))->toBe(2);
});
