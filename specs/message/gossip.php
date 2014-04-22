<?php

namespace jubianchi\gossip\specs\message;

use atoum;
use jubianchi\gossip\node\person;
use jubianchi\gossip\message\gossip as testedClass;

class gossip extends atoum\spec
{
    public function it_should_be_a_message()
    {
        $this->testedClass->implements('jubianchi\gossip\message');
    }

    public function it_should_construct_with_a_message_and_a_source()
    {
        $this
            ->object(new testedClass(uniqid(), new person(uniqid())))
        ;
    }

    public function it_should_cast_to_string_as_its_name()
    {
        $this
            ->given(
                $message = uniqid(),
                $gossip = new testedClass($message, new person(uniqid()))
            )
            ->then
                ->castToString($gossip)->isEqualTo($message)
        ;
    }

    public function it_should_not_tell_message_back_to_teller()
    {
        $this
            ->given(
                $teller = new \mock\jubianchi\gossip\node\person(uniqid()),
                $person = new person(uniqid(), array($teller)),
                $gossip = new testedClass(uniqid(), $teller)
            )
            ->when($gossip->tell($teller, $person))
            ->then
                ->mock($teller)
                    ->call('listen')->withArguments($person, $gossip)->never()
        ;
    }

    public function it_should_mark_people_as_aware_after_telling()
    {
        $this
            ->given(
                $teller = new \mock\jubianchi\gossip\node\person(uniqid()),
                $person = new person(uniqid(), array($teller)),
                $gossip = new testedClass(uniqid(), $teller)
            )
            ->then
                ->boolean($gossip->isAware($person))->isFalse()
            ->when($gossip->tell($person, $teller))
            ->then
                ->boolean($gossip->isAware($person))->isTrue()
        ;
    }
} 
