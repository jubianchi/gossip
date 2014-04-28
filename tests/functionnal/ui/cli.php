<?php

namespace jubianchi\gossip\tests\functionnal\ui;

use jubianchi\gossip\writer;
use jubianchi\gossip\writers;

class cli
{
    public function __construct(writer $writer = null)
    {
        $this->writer = $writer ?: new writers\stdout();
    }

    public function receive()
    {

    }

    public function send()
    {

    }
} 
