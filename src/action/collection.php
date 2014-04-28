<?php

namespace jubianchi\gossip\action;


use jubianchi\gossip\action;
use jubianchi\gossip\node;
use jubianchi\gossip\behaviors;

class collection implements behaviors\collection\matchable
{
	protected $items = array();

	public function add(action $item)
	{
		$this->items[] = $item;

		return $this;
	}

	public function first()
	{
		return reset($this->items);
	}

	public function last()
	{
		return end($this->items);
	}

	public function forEachMatch($pattern, callable $callback)
	{
		foreach ($this->items as $item) {
			$item->ifMatch($pattern, $callback);
		}

		return $this;
	}

	public function forEachNotMatch($pattern, callable $callback)
	{
		foreach ($this->items as $item) {
			$item->ifNotMatch($pattern, $callback);
		}

		return $this;
	}

	public function walk(callable $callback)
	{
		foreach ($this->items as $item) {
			$callback($item);
		}

		return $this;
	}
} 