<?php

namespace App\Home\Event;

use Framework\Event\Event;

class SignUpEvent extends Event
{

    public function __construct()
    {
        $this->setName('signup');
        $this->setTarget('toto.txt');
    }
}
