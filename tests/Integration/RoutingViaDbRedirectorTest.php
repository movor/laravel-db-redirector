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

        RedirectRule::truncate();
    }

    public function test_non_default_redirect_status_code()
    {
        RedirectRule::create([
            'origin' => '/one',
            'destination' => '/two',
            'status_code' => 302
        ]);

        $this->get('/one')
            ->assertRedirect('/two')
            ->assertStatus(302);

        RedirectRule::truncate();
    }

    public function test_route_can_use_single_named_param()
    {
        RedirectRule::create([
            'origin' => '/one/{a}/two',
            'destination' => '/three/{a}'
        ]);

        $this->get('/one/a/two')
            ->assertRedirect('/three/a');

        RedirectRule::truncate();
    }

    public function test_route_can_use_multiple_named_params()
    {
        RedirectRule::create([
            'origin' => '/one/{a}/{b}/two/{c}',
            'destination' => '/{c}/{b}/{a}/three'
        ]);

        $this->get('/one/a/b/two/c')
            ->assertRedirect('/c/b/a/three');

        RedirectRule::truncate();
    }

    public function test_route_can_use_multiple_named_params_in_one_segment()
    {
        RedirectRule::create([
            'origin' => '/one/two/{a}-{b}/{c}',
            'destination' => '/three/{a}/four/{b}/{a}-{c}'
        ]);

        $this->get('/one/two/a-b/c')
            ->assertRedirect('/three/a/four/b/a-c');

        RedirectRule::truncate();
    }

    public function test_route_can_use_optional_parameters_as_wildcards()
    {
        RedirectRule::create([
            'origin' => '/one/{a?}/{b?}',
            'destination' => '/two'
        ]);

        $this->get('/one')
            ->assertRedirect('/two');

        $this->get('/one/a')
            ->assertRedirect('/two');

        $this->get('/one/a/b')
            ->assertRedirect('/two');

        RedirectRule::truncate();
    }

    public function test_router_can_redirect_more_than_once()
    {
        RedirectRule::create([
            'origin' => '/one',
            'destination' => '/two'
        ]);

        RedirectRule::create([
            'origin' => '/three',
            'destination' => '/four'
        ]);

        // TODO.IMPROVE
        // This is actually working but i'm not sure how to test
        // chained redirects. For now we'll test one by one.

        $this->get('one')
            ->assertRedirect('/two');

        $this->get('two')
            ->assertRedirect('/four');

        RedirectRule::truncate();
    }
}