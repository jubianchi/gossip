<?php

namespace jubianchi\gossip\behaviors\matchable;

use jubianchi\gossip\messages;

interface gossip
{
	public function ifMatch(messages\gossip $gossip, callable $callback);
	public function ifNotMatch(messages\gossip $gossip, callable $callback);
}