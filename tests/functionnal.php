<?php

namespace jubianchi\gossip\tests;

use jubianchi\gossip\action;
use jubianchi\gossip\actions;
use jubianchi\gossip\condition;
use jubianchi\gossip\nodes\person;
use jubianchi\gossip\messages\gossip;
use jubianchi\gossip\writable\decorator\collection;
use jubianchi\gossip\writable\decorators\append;
use jubianchi\gossip\writable\decorators\cell;
use jubianchi\gossip\writable\decorators\color;
use jubianchi\gossip\writable\decorators\padding;
use jubianchi\gossip\writable\raw;
use jubianchi\gossip\writers\buffer;
use jubianchi\gossip\writers\stdout;

require_once __DIR__ . '/../vendor/autoload.php';

/*$writer = new stdout();

$sendPrompt = (new collection())
    ->add(new cell(10, cell::RIGHT))
    ->add(new color(color::GREEN))
    ->add(new append(' >'))
;

$receivePrompt = (new collection())
    ->add(new cell(10, cell::RIGHT))
    ->add(new color(color::GREEN))
    ->add(new append(' <'))
;

$debugPrompt = (new collection())
    ->add(new cell(10, cell::RIGHT))
    ->add(new color(color::PURPLE))
    ->add(new append(' |'))
;

$name = (new collection())
    ->add(new color(color::GREEN))
    ->add(
        $padding = (new collection())
            ->add($paddingLeft = new padding(1, ' '))
            ->add(new padding(1, ' ', padding::RIGHT))
    )
;

$noticeName = (new collection())
    ->add(new color(color::PURPLE, null, true))
    ->add(
        $padding = (new collection())
            ->add($paddingLeft = new padding(1, ' '))
            ->add(new padding(1, ' ', padding::RIGHT))
    )
;

$message = (new collection())
    ->add(new color(color::YELLOW))
    ->add($padding)
;

$debug = new condition(function() { return isset($_SERVER['argv'][1]) &&  $_SERVER['argv'][1] === '--debug'; });

$log = new action(
	'/.+/',
	function(gossip $gossip, person $from, person $to, person $source) use ($writer, $receivePrompt, $name, $message) {
		$writer
			->write($receivePrompt->decorate($to))
			->writeString(' Received')
			->write($message->decorate($gossip))
			->writeString('from')
			->write($name->decorate($from))
			->writeString('(originally from')
			->write($name->decorate($source))
			->writeString(')')
			->writeString(PHP_EOL)
		;
	}
);

$report = new actions\conditional(
	'/.+/',
	function(gossip $gossip, person $from, person $to, person $source) use ($writer, $debugPrompt, $paddingLeft) {
		$buffer = new buffer();
		$to->writeFriendsTo($buffer);

		$writer
			->write($debugPrompt->decorate($to))
			->writeString(' My friends:')
			->write($paddingLeft->decorate($buffer))
			->writeString(PHP_EOL)
		;
	},
	$debug
);

$join = new action(
	'/join/',
	function(gossip $gossip, person $from, person $to, person $source) use ($writer, $debugPrompt, $sendPrompt, $name, $noticeName, $message) {
		$writer
			->write($sendPrompt->decorate($to))
            ->write($noticeName->decorate($source))
			->write((new color(color::PURPLE))->decorate(new raw('joined')))
			->writeString(PHP_EOL)
		;

		$gossip->addFriend($to, $source);

		$hello = new gossip('Hello', $to);

		$writer
			->write($sendPrompt->decorate($to))
			->writeString(' Sending')
			->write($message->decorate($hello))
			->writeString('to')
			->write($name->decorate($source))
			->writeString(PHP_EOL)
		;

		$source->listen($to, $hello);
	}
);*/

$john = new person('john');
$david = new person('david');
$rasmus = new person('rasmus');
$jubianchi = new person('jubianchi');

$room = new functionnal\room($john);

$room->enter($david);
$room->enter($rasmus);
$room->enter($jubianchi);
