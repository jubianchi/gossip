<?php

namespace jubianchi\gossip\writable;


use jubianchi\gossip\behaviors\writable;
use jubianchi\gossip\writer;

class raw implements writable
{
    protected $raw;

    public function __construct($raw)
    {
        $this->raw = $raw;
    }

    public function writeTo(writer $writer)
    {
        $writer->writeString($this->raw);

        return $this;
    }
} 
