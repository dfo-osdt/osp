<?php

use Carbon\Carbon;

test('check that all federal holidays are recongized', function () {

    // New Year's Day
    expect(Carbon::parse('2024-01-01')->isHoliday())->toBeTrue();

    // Good Friday
    expect(Carbon::parse('2024-03-29')->isHoliday())->toBeTrue();

    // Easter Monday
    expect(Carbon::parse('2024-04-01')->isHoliday())->toBeTrue();

    // Victoria Day
    expect(Carbon::parse('2024-05-20')->isHoliday())->toBeTrue();

    // Canada Day
    expect(Carbon::parse('2024-07-01')->isHoliday())->toBeTrue();

    // Civic Holiday
    expect(Carbon::parse('2024-08-05')->isHoliday())->toBeTrue();

    // Labour Day
    expect(Carbon::parse('2024-09-02')->isHoliday())->toBeTrue();

    // National Day for Truth and Reconciliation
    expect(Carbon::parse('2024-09-30')->isHoliday())->toBeTrue();

    // Thanksgiving Day
    expect(Carbon::parse('2024-10-14')->isHoliday())->toBeTrue();

    // Christmas Day
    expect(Carbon::parse('2024-12-25')->isHoliday())->toBeTrue();

    // Boxing Day
    expect(Carbon::parse('2024-12-26')->isHoliday())->toBeTrue();

});

test('system accurately calculates 10 buisness days', function(){

    expect(Carbon::parse('2024-03-28')->addBusinessDays(10)->format('Y-m-d'))->toBe('2024-04-15');
    expect(Carbon::parse('2024-04-13')->addBusinessDays(10)->format('Y-m-d'))->toBe('2024-05-26');

});
