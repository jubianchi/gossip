<?php

namespace jubianchi\gossip\tests;

use jubianchi\gossip\action;
use jubianchi\gossip\actions;
use jubianchi\gossip\condition;
use jubianchi\gossip\nodes\person;
use jubianchi\gossip\messages\gossip;
use jubianchi\gossip\writers\buffer;
use jubianchi\gossip\writers\stdout;

require_once __DIR__ . '/../vendor/autoload.php';


$writer = new stdout();
$debug = new condition(function() { return isset($_SERVER['argv'][1]) &&  $_SERVER['argv'][1] === '--debug'; });
$log = new actions\conditional(
	'/.+/',
	function(gossip $gossip, person $from, person $to, person $source) use ($writer) {
		$writer
			->writeString(" \033[35m")
			->write($to)
			->writeString("\033[0m | Received \033[33m'")
			->write($gossip)
			->writeString("'\033[0m from \033[32m")
			->write($from)
			->writeString("'\033[0m (originally from \033[32m")
			->write($source)
			->writeString("\033[0m)")
			->writeString(PHP_EOL)
		;
	},
	$debug
);

$report = new actions\conditional(
	'/.+/',
	function(gossip $gossip, person $from, person $to, person $source) use ($writer) {
		$buffer = new buffer();
		$to->writeFriendsTo($buffer);

		$writer
			->writeString(" \033[35m")
			->write($to)
			->writeString("\033[0m | My friends: ")
			->write($buffer)
			->writeString(PHP_EOL)
		;
	},
	$debug
);

$join = new action(
	'/join/',
	function(gossip $gossip, person $from, person $to, person $source) use ($writer) {
		$writer
			->writeString(" \033[32m")
			->write($to)
			->writeString("\033[0m > \033[32m")
			->write($source)
			->writeString("\033[0m joined")
			->writeString(PHP_EOL)
		;

		$gossip->addFriend($to, $source);

		$hello = new gossip('Hello', $to);

		$writer
			->writeString(" \033[32m")
			->write($to)
			->writeString("\033[0m > Sending \033[33m'")
			->write($hello)
			->writeString("'\033[0m to \033[32m")
			->write($source)
			->writeString("\033[0m")
			->writeString(PHP_EOL)
		;

		$source->listen($to, $hello);
	}
);

$john = (new person('john'))->on($log)->on($join)->on($report);
$david = (new person('david'))->on($log)->on($join)->on($report);
$rasmus = (new person('rasmus'))->on($log)->on($join)->on($report);
$jubianchi = (new person('jubianchi'))->on($log)->on($join)->on($report);

(new gossip('join', $david))->tell($john);
(new gossip('join', $rasmus))->tell($david);
(new gossip('join', $jubianchi))->tell($john);
