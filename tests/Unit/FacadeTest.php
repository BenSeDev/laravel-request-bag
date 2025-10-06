<?php

declare(strict_types=1);

use Bensedev\RequestBag\Facades\RequestBag as RequestBagFacade;
use Bensedev\RequestBag\RequestBag;
use Bensedev\RequestBag\RequestBagServiceProvider;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Facade;

beforeEach(function () {
    $this->app = new Container();
    $this->app->singleton('app', fn () => $this->app);

    Facade::setFacadeApplication($this->app);

    $provider = new RequestBagServiceProvider($this->app);
    $provider->register();
});

afterEach(function () {
    Facade::clearResolvedInstances();
    Facade::setFacadeApplication(null);
});

test('facade resolves to RequestBag instance', function () {
    $instance = RequestBagFacade::getFacadeRoot();

    expect($instance)->toBeInstanceOf(RequestBag::class);
});

test('facade can add and get values', function () {
    RequestBagFacade::add('key', 'value');

    expect(RequestBagFacade::get('key'))->toBe('value');
});

test('facade methods are chainable', function () {
    $result = RequestBagFacade::add('key1', 'value1')
        ->add('key2', 'value2')
    ;

    expect($result)
        ->toBeInstanceOf(RequestBag::class)
        ->and(RequestBagFacade::has('key1'))->toBeTrue()
        ->and(RequestBagFacade::has('key2'))->toBeTrue()
    ;
});

test('facade is request scoped and returns same instance', function () {
    RequestBagFacade::add('key', 'value');

    $instance1 = RequestBagFacade::getFacadeRoot();
    $instance2 = RequestBagFacade::getFacadeRoot();

    expect($instance1)
        ->toBe($instance2)
        ->and($instance1->get('key'))->toBe('value')
        ->and($instance2->get('key'))->toBe('value')
    ;
});

test('facade all methods work correctly', function () {
    RequestBagFacade::add('key1', 'value1');
    RequestBagFacade::add('key2', 'value2');

    expect(RequestBagFacade::all())->toBe([
        'key1' => 'value1',
        'key2' => 'value2',
    ]);
});

test('facade has method works correctly', function () {
    RequestBagFacade::add('key', 'value');

    expect(RequestBagFacade::has('key'))
        ->toBeTrue()
        ->and(RequestBagFacade::has('nonexistent'))->toBeFalse()
    ;
});

test('facade exists method works correctly', function () {
    RequestBagFacade::add('key', '');

    expect(RequestBagFacade::exists('key'))
        ->toBeTrue()
        ->and(RequestBagFacade::exists('nonexistent'))->toBeFalse()
    ;
});

test('facade remove method works correctly', function () {
    RequestBagFacade::add('key', 'value');
    RequestBagFacade::remove('key');

    expect(RequestBagFacade::has('key'))->toBeFalse();
});

test('facade clear method works correctly', function () {
    RequestBagFacade::add('key1', 'value1');
    RequestBagFacade::add('key2', 'value2');
    RequestBagFacade::clear();

    expect(RequestBagFacade::all())->toBe([]);
});

test('facade merge method works correctly', function () {
    RequestBagFacade::add('key1', 'value1');
    RequestBagFacade::merge(['key2' => 'value2', 'key3' => 'value3']);

    expect(RequestBagFacade::all())->toBe([
        'key1' => 'value1',
        'key2' => 'value2',
        'key3' => 'value3',
    ]);
});
