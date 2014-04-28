<?php

namespace jubianchi\gossip\actions;

use jubianchi\gossip\action;
use jubianchi\gossip\condition;
use jubianchi\gossip\messages\gossip;
use jubianchi\gossip\node;

class conditional extends action
{
	protected $condition;

	public function __construct($pattern, callable $callback, condition $condition)
	{
		parent::__construct($pattern, $callback);

		$this->condition = $condition;
	}

	public function run(gossip $gossip, node $from, node $to, node $source)
	{
		$this->condition->ifTrue(function() use ($gossip, $from, $to, $source) {
			parent::run($gossip, $from, $to, $source);
		});

		return $this;
	}
} 