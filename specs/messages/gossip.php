<?php

namespace jubianchi\gossip\specs\messages;

use atoum;
use jubianchi\gossip\node\collection;
use jubianchi\gossip\nodes\person;
use jubianchi\gossip\messages\gossip as testedClass;

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

    public function it_should_not_tell_message_back_to_teller()
    {
        $this
            ->given(
                $teller = new \mock\jubianchi\gossip\nodes\person(uniqid()),
                $person = new person(uniqid(), (new collection())->add($teller)),
                $gossip = new testedClass(uniqid(), $teller)
            )
            ->when($gossip->tell($teller, $person))
            ->then
                ->mock($teller)
                    ->call('listen')->withArguments($person, $gossip)->never()
        ;
    }

	public function it_should_create_relationship_with_teller()
	{
		$this
			->given(
				$friend = new \mock\jubianchi\gossip\nodes\person(uniqid()),
				$otherFriend = new \mock\jubianchi\gossip\nodes\person(uniqid()),
				$gossip = new testedClass(uniqid(), $friend)
			)
			->then
				->invoking->addFriend($otherFriend)->on($gossip)
					->shouldReturn->object->isIdenticalTo($gossip)
				->mock($friend)
					->call('link')->withArguments($otherFriend)->once()
				->mock($otherFriend)
					->call('link')->withArguments($friend)->once()
		;
	}

	public function it_should_create_relationship_between_persons()
	{
		$this
			->given(
				$friend = new \mock\jubianchi\gossip\nodes\person(uniqid()),
				$otherFriend = new \mock\jubianchi\gossip\nodes\person(uniqid()),
				$gossip = new testedClass(uniqid(), new \mock\jubianchi\gossip\nodes\person(uniqid()))
			)
			->then
				->invoking->addFriend($otherFriend, $friend)->on($gossip)
					->shouldReturn->object->isIdenticalTo($gossip)
				->mock($friend)
					->call('link')->withArguments($otherFriend)->once()
				->mock($otherFriend)
					->call('link')->withArguments($friend)->once()
		;
	}
} 
