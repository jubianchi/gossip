<?php

namespace jubianchi\gossip\writers;

use jubianchi\gossip\behaviors;
use jubianchi\gossip\writer;

class buffer implements writer, behaviors\writable
{
	protected $buffer;

	public function write(behaviors\writable $writable)
	{
		$writable->writeTo($this);

		return $this;
	}

	public function writeString($string)
	{
		$this->buffer .= $string;

		return $this;
	}

	public function writeTo(writer $writer)
	{
		$writer->writeString($this->buffer);

		return $this->reset();
	}

    public function flush(callable $callback)
    {
        $callback($this->buffer);

        return $this->reset();
    }

	public function reset()
	{
		$this->buffer = '';

		return $this;
	}
} 
