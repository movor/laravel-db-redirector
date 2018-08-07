<?php

namespace Movor\LaravelDbRedirector\Test\Integration;

use Movor\LaravelDbRedirector\Models\RedirectRule;
use Movor\LaravelDbRedirector\Test\TestCase;

class RoutingViaDbRedirectorTest extends TestCase
{
    public function test_simple_redirect()
    {
        RedirectRule::create([
            'origin' => '/one',
            'destination' => '/two'
        ]);

        $this->get('/one')
            ->assertRedirect('/two')
            ->assertStatus(301);
    }

    public function test_non_default_redirect_status_code()
    {
        RedirectRule::create([
            'origin' => '/one/two/five',
            'destination' => '/two',
            'status_code' => 302
        ]);

        $this->get('/one/two/five')
            ->assertRedirect('/two')
            ->assertStatus(302);
    }

    public function test_route_can_use_single_named_param()
    {
        RedirectRule::create([
            'origin' => '/one/{a}/two',
            'destination' => '/three/{a}'
        ]);

        $this->get('/one/a/two')
            ->assertRedirect('/three/a');
    }

    public function test_route_can_use_multiple_named_params()
    {
        RedirectRule::create([
            'origin' => '/one/{a}/{b}/two/{c}',
            'destination' => '/{c}/{b}/{a}/three'
        ]);

        $this->get('/one/a/b/two/c')
            ->assertRedirect('/c/b/a/three');
    }

    public function test_route_can_use_multiple_named_params_in_one_segment()
    {
        RedirectRule::create([
            'origin' => '/one/two/{a}-{b}/{c}',
            'destination' => '/three/{a}/four/{b}/{a}-{c}'
        ]);

        $this->get('/one/two/a-b/c')
            ->assertRedirect('/three/a/four/b/a-c');
    }

    public function test_route_can_use_optional_parameters_as_wildcards()
    {
        RedirectRule::create([
            'origin' => '/five/{a?}/{b?}',
            'destination' => '/six'
        ]);

        $this->get('/five')
            ->assertRedirect('/six');

        $this->get('/five/a')
            ->assertRedirect('/six');

        $this->get('/five/a/b')
            ->assertRedirect('/six');
    }

    public function test_router_can_redirect_more_than_once()
    {
        RedirectRule::create([
            'origin' => '/x',
            'destination' => '/y'
        ]);

        RedirectRule::create([
            'origin' => '/y',
            'destination' => '/z'
        ]);

        $this->followingRedirects()
            ->get('/x')
            ->assertRedirect('/z');
    }
}