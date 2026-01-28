<?php

use App\Rules\Isbn;
use Illuminate\Support\Facades\Validator;

test('valid ISBN-13 passes validation', function (): void {
    $validator = Validator::make(
        ['isbn' => '9780134685991'],
        ['isbn' => new Isbn]
    );

    expect($validator->passes())->toBeTrue();
});

test('invalid check digit fails validation', function (): void {
    $validator = Validator::make(
        ['isbn' => '9780134685990'],
        ['isbn' => new Isbn]
    );

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('isbn'))->toBeTrue();
});

test('empty value passes validation', function (): void {
    $validator = Validator::make(
        ['isbn' => ''],
        ['isbn' => new Isbn]
    );

    expect($validator->passes())->toBeTrue();
});

test('null value passes validation', function (): void {
    $validator = Validator::make(
        ['isbn' => null],
        ['isbn' => new Isbn]
    );

    expect($validator->passes())->toBeTrue();
});

test('ISBN with hyphens fails validation', function (): void {
    $validator = Validator::make(
        ['isbn' => '978-0-13-468599-1'],
        ['isbn' => new Isbn]
    );

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('isbn'))->toBeTrue();
});

test('ISBN with spaces fails validation', function (): void {
    $validator = Validator::make(
        ['isbn' => '978 0 13 468599 1'],
        ['isbn' => new Isbn]
    );

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('isbn'))->toBeTrue();
});

test('too few digits fails validation', function (): void {
    $validator = Validator::make(
        ['isbn' => '978013468599'],
        ['isbn' => new Isbn]
    );

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('isbn'))->toBeTrue();
});

test('too many digits fails validation', function (): void {
    $validator = Validator::make(
        ['isbn' => '97801346859912'],
        ['isbn' => new Isbn]
    );

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('isbn'))->toBeTrue();
});

test('ISBN with letters fails validation', function (): void {
    $validator = Validator::make(
        ['isbn' => '978013468599X'],
        ['isbn' => new Isbn]
    );

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('isbn'))->toBeTrue();
});

test('multiple valid ISBN-13 numbers pass validation', function (string $isbn): void {
    $validator = Validator::make(
        ['isbn' => $isbn],
        ['isbn' => new Isbn]
    );

    expect($validator->passes())->toBeTrue();
})->with([
    '9780134685991',
    '9780306406157',
    '9783161484100',
    '9780140449136',
]);
