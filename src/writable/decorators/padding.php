<?php

namespace jubianchi\gossip\writable\decorators;

use jubianchi\gossip\behaviors\writable;
use jubianchi\gossip\writable\decorator;
use jubianchi\gossip\writer;

class padding implements decorator
{
    const LEFT = 1;
    const RIGHT = 2;

    protected $decorated;
    protected $padding;
    protected $length;
    protected $char;

    public function __construct($padding, $char = null, $type = null)
    {
        $this->padding = $padding;
        $this->char = $char ?: ' ';
        $this->type = $type ?: self::LEFT;
    }

    public function decorate(writable $writable)
    {
        $this->decorated = $writable;

        return $this;
    }

    public function writeTo(writer $writer)
    {
        if (self::LEFT === $this->type) {
            $writer->writeString(str_repeat($this->char, $this->padding));
        }

        $writer->write($this->decorated);

        if (self::RIGHT === $this->type) {
            $writer->writeString(str_repeat($this->char, $this->padding));
        }

        return $this;
    }
} 
