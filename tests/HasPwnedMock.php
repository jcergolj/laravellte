<?php

namespace Tests;

use Valorin\Pwned\Pwned;

trait HasPwnedMock
{
    /**
     * Mock pwned rule.
     *
     * @param  bool  $return
     * @return null
     */
    public function mockPwned($return = true)
    {
        $this->partialMock(Pwned::class)->shouldReceive('passes')->andReturn($return);
    }
}
