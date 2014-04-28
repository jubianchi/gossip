<?php

namespace jubianchi\gossip\writable\decorators;

use jubianchi\gossip\behaviors\writable;
use jubianchi\gossip\writable\decorator;
use jubianchi\gossip\writer;

class color implements decorator
{
    const BLACK = 0;
    const RED = 1;
    const GREEN = 2;
    const YELLOW = 3;
    const BLUE = 4;
    const PURPLE = 5;
    const CYAN = 6;
    const GRAY = 7;

    protected $decorated;
    protected $foreground;
    protected $background;
    protected $bold;

    public function __construct($foreground = null, $background = null, $bold = false)
    {
        $this->foreground = $foreground ?: 8;
        $this->background = $background ?: 8;
        $this->bold = $bold;
    }

    public function decorate(writable $writable)
    {
        $this->decorated = $writable;

        return $this;
    }

    public function writeTo(writer $writer)
    {
        $style = [];

        if (true === $this->bold) {
            $style[] = 1;
        }

        if (null !== $this->foreground) {
            $style[] = ($this->foreground + 30);
        }

        if (null !== $this->background) {
            $style[] = ($this->background + 40);
        }

        $writer
            ->writeString(sprintf("\033[%sm", implode(';', $style)))
            ->write($this->decorated)
            ->writeString("\033[0m")
        ;

        return $this;
    }
} 
