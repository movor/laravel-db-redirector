<?php

namespace Movor\LaravelDbRedirector\Test\Integration;

use Movor\LaravelDbRedirector\Models\RedirectRule;
use Movor\LaravelDbRedirector\Test\TestCase;

class RedirectRuleModelTest extends TestCase
{
    public function test_it_update_hits_properly()
    {
        $redirectRule = RedirectRule::create([
            'origin' => 'ten/ten',
            'destination' => 'ten/eleven'
        ]);

        $redirectRule = RedirectRule::find($redirectRule->id);

        $this->assertEquals($redirectRule->hits, 0);
        $this->assertEquals($redirectRule->last_hit_at, null);

        $redirectRule->hit();
        $redirectRule->hit();

        $redirectRule = RedirectRule::find($redirectRule->id);

        $this->assertEquals($redirectRule->hits, 2);
        $this->assertNotNull($redirectRule->last_hit_at);
    }

    public function test_it_sets_default_status_code_when_null_passed()
    {
        $redirectRule = new RedirectRule;
        $redirectRule->origin = '/eleven';
        $redirectRule->destination = '/twelve';
        $redirectRule->status_code = null;
        $redirectRule->save();

        $redirectRule = RedirectRule::find($redirectRule->id);

        $this->assertEquals(301, $redirectRule->status_code);
    }
}