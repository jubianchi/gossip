<?php

namespace jubianchi\gossip\writable;

use jubianchi\gossip\behaviors\writable;

interface decorator extends writable
{
    public function decorate(writable $writable);
} 
