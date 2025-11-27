<?php

test('check that all federal holidays are recongized', function (): void {

    // New Year's Day if it falls on a Sunday
    expect(\Illuminate\Support\Facades\Date::parse('2023-01-02')->isHoliday())->toBeTrue();

    // New Year's Day
    expect(\Illuminate\Support\Facades\Date::parse('2024-01-01')->isHoliday())->toBeTrue();

    // Good Friday
    expect(\Illuminate\Support\Facades\Date::parse('2024-03-29')->isHoliday())->toBeTrue();

    // Easter Monday
    expect(\Illuminate\Support\Facades\Date::parse('2024-04-01')->isHoliday())->toBeTrue();

    // Victoria Day
    expect(\Illuminate\Support\Facades\Date::parse('2024-05-20')->isHoliday())->toBeTrue();

    // St Jean Baptiste Day
    expect(\Illuminate\Support\Facades\Date::parse('2024-06-24')->isHoliday())->toBeTrue();

    // Canada Day
    expect(\Illuminate\Support\Facades\Date::parse('2024-07-01')->isHoliday())->toBeTrue();

    // Civic Holiday
    expect(\Illuminate\Support\Facades\Date::parse('2024-08-05')->isHoliday())->toBeTrue();

    // Labour Day
    expect(\Illuminate\Support\Facades\Date::parse('2024-09-02')->isHoliday())->toBeTrue();

    // National Day for Truth and Reconciliation
    expect(\Illuminate\Support\Facades\Date::parse('2024-09-30')->isHoliday())->toBeTrue();

    // Thanksgiving Day
    expect(\Illuminate\Support\Facades\Date::parse('2024-10-14')->isHoliday())->toBeTrue();

    // Christmas Day
    expect(\Illuminate\Support\Facades\Date::parse('2024-12-25')->isHoliday())->toBeTrue();

    // Boxing Day
    expect(\Illuminate\Support\Facades\Date::parse('2024-12-26')->isHoliday())->toBeTrue();

});

test('system accurately calculates 10 buisness days forward', function (): void {

    expect(\Illuminate\Support\Facades\Date::parse('2024-03-28')->addBusinessDays(10)->format('Y-m-d'))->toBe('2024-04-15');
    expect(\Illuminate\Support\Facades\Date::parse('2024-04-13')->addBusinessDays(10)->format('Y-m-d'))->toBe('2024-04-26');
    expect(\Illuminate\Support\Facades\Date::parse('2024-06-21')->addBusinessDays(10)->format('Y-m-d'))->toBe('2024-07-09');

});
