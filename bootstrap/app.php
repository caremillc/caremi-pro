<?php declare(strict_types=1);

$providers = [
    \App\Providers\EventServiceProvider::class
];

foreach ($providers as $providerClass) {
    $provider = $container->get($providerClass);
    $provider->register();
}
