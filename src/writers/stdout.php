<?php

namespace jubianchi\gossip\writers;

use jubianchi\gossip\behaviors;
use jubianchi\gossip\writer;

class stdout implements writer
{
	public function write(behaviors\writable $writable)
	{
		$writable->writeTo($this);

		return $this;
	}

	public function writeString($string)
	{
		echo $string;

		return $this;
	}
} 