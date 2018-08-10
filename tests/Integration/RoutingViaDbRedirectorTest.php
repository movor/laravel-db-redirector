<?php

namespace Movor\LaravelDbRedirector\Test\Integration;

use Movor\LaravelDbRedirector\Models\RedirectRule;
use Movor\LaravelDbRedirector\Test\TestCase;

class RoutingViaDbRedirectorTest extends TestCase
{
    public function test_simple_redirect()
    {
        $redirectRule = RedirectRule::create([
            'origin' => '/one',
            'destination' => '/two'
        ]);

        $this->get('/one')
            ->assertRedirect('/two')
            ->assertStatus(301);

        $redirectRule->delete();
    }

    public function test_non_default_redirect_status_code()
    {
        $redirectRule = RedirectRule::create([
            'origin' => '/one',
            'destination' => '/two',
            'status_code' => 302
        ]);

        $this->get('/one')
            ->assertRedirect('/two')
            ->assertStatus(302);

        $redirectRule->delete();
    }

    public function test_route_can_use_single_named_param()
    {
        $redirectRule = RedirectRule::create([
            'origin' => '/one/{a}/two',
            'destination' => '/three/{a}'
        ]);

        $this->get('/one/a/two')
            ->assertRedirect('/three/a');

        $redirectRule->delete();
    }

    public function test_route_can_use_multiple_named_params()
    {
        $redirectRule = RedirectRule::create([
            'origin' => '/one/{a}/{b}/two/{c}',
            'destination' => '/{c}/{b}/{a}/three'
        ]);

        $this->get('/one/a/b/two/c')
            ->assertRedirect('/c/b/a/three');

        $redirectRule->delete();
    }

    public function test_route_can_use_multiple_named_params_in_one_segment()
    {
        $redirectRule = RedirectRule::create([
            'origin' => '/one/two/{a}-{b}/{c}',
            'destination' => '/three/{a}/four/{b}/{a}-{c}'
        ]);

        $this->get('/one/two/a-b/c')
            ->assertRedirect('/three/a/four/b/a-c');

        $redirectRule->delete();
    }

    public function test_route_can_use_optional_parameters_as_wildcards()
    {
        $redirectRule = RedirectRule::create([
            'origin' => '/one/{a?}/{b?}',
            'destination' => '/two'
        ]);

        $this->get('/one')
            ->assertRedirect('/two');

        $this->get('/one/a')
            ->assertRedirect('/two');

        $this->get('/one/a/b')
            ->assertRedirect('/two');

        $redirectRule->delete();
    }

    public function test_router_can_do_chained_redirects()
    {
        RedirectRule::create([
            'origin' => '/one',
            'destination' => '/two'
        ]);

        RedirectRule::create([
            'origin' => '/two',
            'destination' => '/three'
        ]);

        // TODO.IMPROVE
        // This is actually working but i'm not sure how to test
        // chained redirects. For now we'll test one by one.

        $this->get('/one')
            ->assertRedirect('/two');

        $this->get('/two')
            ->assertRedirect('/three');

        RedirectRule::truncate();
    }
}