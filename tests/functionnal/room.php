<?php

namespace jubianchi\gossip\tests\functionnal;

use jubianchi\gossip\messages\gossip;
use jubianchi\gossip\node\collection;
use jubianchi\gossip\nodes\person;

class room
{
    protected $persons;
    protected $board;

    public function __construct(person $owner, board $board = null)
    {
        $this->persons = (new collection())->add($owner);
        $this->board = ($board ?: new board());

        $this->board->attach($owner);
    }

    public function enter(person $person)
    {
        (new gossip('join', $person))->tell($this->persons->last());

        $this->persons->add($person);
        $this->board->attach($person);

        return $this;
    }
} 
