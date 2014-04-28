<?php

namespace jubianchi\gossip;


class condition
{
	protected $condition;

	public function __construct(callable $condition)
	{
		$this->condition = $condition;
	}

	public function ifTrue(callable $callback)
	{
		if (call_user_func($this->condition) === true) {
			$callback();
		}

		return $this;
	}

	public function ifFalse(callable $callback)
	{
		if (call_user_func($this->condition) === false) {
			$callback();
		}

		return false;
	}
} 