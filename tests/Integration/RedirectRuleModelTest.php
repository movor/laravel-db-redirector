<?php

namespace Movor\LaravelDbRedirector\Test\Integration;

use Movor\LaravelDbRedirector\Models\RedirectRule;
use Movor\LaravelDbRedirector\Test\TestCase;

class RedirectRuleModelTest extends TestCase
{
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