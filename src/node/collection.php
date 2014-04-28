<?php

namespace jubianchi\gossip\node;


use jubianchi\gossip\node;
use jubianchi\gossip\behaviors;
use jubianchi\gossip\writer;

class collection implements behaviors\writable, behaviors\searchable
{
	protected $items = array();

	public function add(node $item)
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

	public function ifFound($mixed, callable $callback)
	{
		if (true === in_array($mixed, $this->items)) {
			$callback($mixed);
		}

		return $this;
	}

	public function ifNotFound($mixed, callable $callback)
	{
		if (false === in_array($mixed, $this->items)) {
			$callback($mixed);
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

	public function writeTo(writer $writer)
	{
		$separator = '';

		$this->walk(function($item) use (& $separator, $writer) {
				$writer
					->writeString($separator)
					->write($item)
				;

				$separator = ', ';
			}
		);

		return $this;
	}
} 