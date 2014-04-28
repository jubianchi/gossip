<?php

namespace jubianchi\gossip\specs\nodes;

use atoum;
use jubianchi\gossip\node\collection;
use jubianchi\gossip\nodes\person as testedClass;

class person extends atoum\spec
{
    public function it_should_be_a_node()
    {
        $this->testedClass->implements('jubianchi\gossip\node');
    }

    public function it_should_construct_with_a_name()
    {
        $this
            ->object(new testedClass(uniqid()))
        ;
    }

    public function it_should_tell_message_to_friends()
    {
        $this
            ->given(
                $friend = new testedClass(uniqid()),
                $otherFriend = new testedClass(uniqid()),
                $person = new testedClass(uniqid(), (new collection())->add($friend)->add($otherFriend)),
                $gossip = new \mock\jubianchi\gossip\messages\gossip(uniqid(), $person)
            )
            ->when($person->tell($gossip))
            ->then
                ->mock($gossip)
                    ->call('tell')->withArguments($friend, $person)->once()
                    ->call('tell')->withArguments($otherFriend, $person)->once()
        ;
    }

    public function it_should_forward_listened_message_to_friends()
    {
        $this
            ->given(
                $friend = new testedClass(uniqid()),
                $otherFriend = new testedClass(uniqid()),
                $teller = new testedClass(uniqid()),
                $gossip = new \mock\jubianchi\gossip\messages\gossip(uniqid(), $teller),
                $person = new testedClass(uniqid(), (new collection())->add($friend)->add($otherFriend))
            )
            ->when($person->listen($teller, $gossip))
            ->then
                ->mock($gossip)
                    ->call('tell')->withArguments($friend, $person)->once()
                    ->call('tell')->withArguments($otherFriend, $person)->once()
        ;
    }
} 
