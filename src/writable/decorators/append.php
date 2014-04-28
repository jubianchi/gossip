<?php

namespace jubianchi\gossip\writable\decorators;

use jubianchi\gossip\behaviors\writable;
use jubianchi\gossip\writable\decorator;
use jubianchi\gossip\writer;

class append implements decorator
{
    protected $decorated;
    protected $string;

    public function __construct($string)
    {
        $this->string = $string;
    }

    public function decorate(writable $writable)
    {
        $this->decorated = $writable;

        return $this;
    }

    public function writeTo(writer $writer)
    {
        $writer
            ->write($this->decorated)
            ->writeString($this->string)
        ;

        return $this;
    }
} 
