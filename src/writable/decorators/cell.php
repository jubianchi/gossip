<?php

namespace jubianchi\gossip\writable\decorators;

use jubianchi\gossip\behaviors\writable;
use jubianchi\gossip\writable\decorator;
use jubianchi\gossip\writer;
use jubianchi\gossip\writers;

class cell implements decorator
{
    const LEFT = 1;
    const RIGHT = 2;

    protected $decorated;
    protected $align;

    public function __construct($width, $align = null, $buffer = null)
    {
        $this->width = $width;
        $this->align = $align ?: self::LEFT;
        $this->buffer = $buffer ?: new writers\buffer();
    }

    public function decorate(writable $writable)
    {
        $this->decorated = $writable;

        return $this;
    }

    public function writeTo(writer $writer)
    {
        $format = '%';

        if (self::LEFT === $this->align) {
            $format .= '-';
        }

        $format .= $this->width . 's';

        $this->buffer
            ->write($this->decorated)
            ->flush(function($buffer) use ($writer, $format) {
                $writer->writeString(sprintf($format, $buffer));
            })
        ;

        return $this;
    }
} 
