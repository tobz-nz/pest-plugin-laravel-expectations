<?php

use Illuminate\Support\Facades\App;
use function Pest\Laravel\post;
use PHPUnit\Framework\ExpectationFailedException;

test('pass', function () {
    $response = post('/validate', ['email' => 'taylor']);

    if (version_compare(App::version(), '8.55', '<')) {
        expect($response)->toBeRedirect();
        return;
    }

    expect($response)->toHaveInvalid(['email']);
});

test('fails', function () {
    $response = post('/validate', ['email' => 'taylor@laravel.com']);

    if (version_compare(App::version(), '8.55', '<')) {
        throw new ExpectationFailedException('Session is missing expected key [errors]');
    }

    expect($response)->toHaveInvalid(['email']);
})->throws(ExpectationFailedException::class, 'Session is missing expected key [errors]');

test('pass with negation', function () {
    $response = post('/validate', ['email' => 'taylor@laravel.com']);

    if (version_compare(App::version(), '8.55', '<')) {
        expect($response)->not->toBeRedirect();
        return;
    }

    expect($response)->not->toHaveInvalid(['email']);
});

test('fails with negation', function () {
    $response = post('/validate');

    if (version_compare(App::version(), '8.55', '<')) {
        throw new ExpectationFailedException('Expecting Illuminate\Testing\TestResponse Object (...) not to have invalid Array (...)');
    }

    expect($response)->not->toHaveInvalid(['email']);
})->throws(ExpectationFailedException::class, 'Expecting Illuminate\Testing\TestResponse Object (...) not to have invalid Array (...)');
