<?php

namespace jubianchi\gossip\specs\node;

use atoum;
use jubianchi\gossip\node\person as testedClass;

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

    public function it_should_cast_to_string_as_its_name()
    {
        $this
            ->given(
                $name = uniqid(),
                $person = new testedClass($name)
            )
            ->then
                ->castToString($person)->isEqualTo($name)
        ;
    }

    public function it_should_tell_message_to_friends()
    {
        $this
            ->given(
                $friend = new testedClass(uniqid()),
                $otherFriend = new testedClass(uniqid()),
                $person = new testedClass(uniqid(), array($friend, $otherFriend)),
                $gossip = new \mock\jubianchi\gossip\message\gossip(uniqid(), $person)
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
                $gossip = new \mock\jubianchi\gossip\message\gossip(uniqid(), $teller),
                $person = new testedClass(uniqid(), array($friend, $otherFriend))
            )
            ->when($person->listen($teller, $gossip))
            ->then
                ->mock($gossip)
                    ->call('tell')->withArguments($friend, $person)->once()
                    ->call('tell')->withArguments($otherFriend, $person)->once()
        ;
    }
} 
