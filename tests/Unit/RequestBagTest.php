<?php

declare(strict_types=1);

use Bensedev\RequestBag\RequestBag;

test('can add and get values', function () {
    $bag = new RequestBag();

    $bag->add('key', 'value');

    expect($bag->get('key'))->toBe('value');
});

test('returns default when key does not exist', function () {
    $bag = new RequestBag();

    expect($bag->get('nonexistent', 'default'))->toBe('default');
});

test('returns null by default when key does not exist', function () {
    $bag = new RequestBag();

    expect($bag->get('nonexistent'))->toBeNull();
});

test('has returns true when key exists and is not empty', function () {
    $bag = new RequestBag();

    $bag->add('key', 'value');

    expect($bag->has('key'))->toBeTrue();
});

test('has returns false when key does not exist', function () {
    $bag = new RequestBag();

    expect($bag->has('nonexistent'))->toBeFalse();
});

test('has returns false when key exists but is empty', function () {
    $bag = new RequestBag();

    $bag->add('key', '');

    expect($bag->has('key'))->toBeFalse();
});

test('has returns false when key exists but is null', function () {
    $bag = new RequestBag();

    $bag->add('key', null);

    expect($bag->has('key'))->toBeFalse();
});

test('exists returns true when key exists even if empty', function () {
    $bag = new RequestBag();

    $bag->add('key', '');

    expect($bag->exists('key'))->toBeTrue();
});

test('can remove values', function () {
    $bag = new RequestBag();

    $bag->add('key', 'value');
    $bag->remove('key');

    expect($bag->has('key'))->toBeFalse();
});

test('can get all values', function () {
    $bag = new RequestBag();

    $bag->add('key1', 'value1');
    $bag->add('key2', 'value2');

    expect($bag->all())->toBe([
        'key1' => 'value1',
        'key2' => 'value2',
    ]);
});

test('can clear all values', function () {
    $bag = new RequestBag();

    $bag->add('key1', 'value1');
    $bag->add('key2', 'value2');
    $bag->clear();

    expect($bag->all())->toBe([]);
});

test('can merge data', function () {
    $bag = new RequestBag();

    $bag->add('key1', 'value1');
    $bag->merge(['key2' => 'value2', 'key3' => 'value3']);

    expect($bag->all())->toBe([
        'key1' => 'value1',
        'key2' => 'value2',
        'key3' => 'value3',
    ]);
});

test('merge overwrites existing keys', function () {
    $bag = new RequestBag();

    $bag->add('key', 'original');
    $bag->merge(['key' => 'updated']);

    expect($bag->get('key'))->toBe('updated');
});

test('methods are chainable', function () {
    $bag = new RequestBag();

    $result = $bag->add('key1', 'value1')
        ->add('key2', 'value2')
        ->remove('key1')
    ;

    expect($result)
        ->toBeInstanceOf(RequestBag::class)
        ->and($bag->has('key1'))->toBeFalse()
        ->and($bag->has('key2'))->toBeTrue()
    ;
});
