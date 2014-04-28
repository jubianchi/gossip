<?php

namespace jubianchi\gossip\writable\decorator;

use jubianchi\gossip\behaviors\writable;
use jubianchi\gossip\writable\decorator;
use jubianchi\gossip\writer;

class collection implements decorator
{
    protected $items = array();
    protected $decorated;

    public function add(decorator $item)
    {
        $this->items[] = $item;

        return $this;
    }

    public function decorate(writable $writable)
    {
        $this->decorated = $writable;

        return $this;
    }

    public function writeTo(writer $writer)
    {
        $writable = $this->decorated;

        foreach ($this->items as $decorator) {
            $writable = $decorator->decorate($writable);
        }

        $writable->writeTo($writer);
    }
} 
