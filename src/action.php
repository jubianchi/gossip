<?php

namespace jubianchi\gossip;


use jubianchi\gossip\behaviors\matchable;
use jubianchi\gossip\messages\gossip;

class action implements matchable\gossip
{
	protected $pattern;
	protected $callback;

	public function __construct($pattern, callable $callback)
	{
		$this->pattern = $pattern;
		$this->callback = $callback;
	}

	public function ifMatch(gossip $gossip, callable $callback)
	{
		$gossip->ifMatch($this->pattern, function(gossip $gossip) use ($callback) {
				$callback($this, $gossip);
			}
		);

		return $this;
	}

	public function ifNotMatch(gossip $gossip, callable $callback)
	{
		$gossip->ifNotMatch($this->pattern, function(gossip $gossip) use ($callback) {
				$callback($this, $gossip);
			}
		);

		return $this;
	}

	public function run(gossip $gossip, node $from, node $to, node $source)
	{
		call_user_func_array($this->callback, array($gossip, $from, $to, $source));

		return $this;
	}
} 