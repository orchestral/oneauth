<?php

namespace spec\Orchestra\OneAuth;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TokenSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Orchestra\OneAuth\Token');
    }

    function it_should_be_invalid_given_null_attributes()
    {
        $this->isValid()->shouldReturn(false);
    }

    function it_should_be_valid_given_correct_attributes()
    {
        $this->beConstructedWith(['token' => 'foobar']);

        $this->isValid()->shouldReturn(true);
    }
}
