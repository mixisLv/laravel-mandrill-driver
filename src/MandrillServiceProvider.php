<?php

namespace mixisLv\LaravelMandrillDriver;

use Illuminate\Support\ServiceProvider;

class MandrillServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app['config']['mail.driver'] == 'mandrill') {
            $this->app['swift.transport']->extend('mandrill', function () {
                $config = array_merge(['secret' => ''], $this->app['config']->get('services.mandrill', []));

                return new MandrillTransport(new \GuzzleHttp\Client($config), $config['secret']);
            });
        }
    }
}
