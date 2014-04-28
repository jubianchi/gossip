<?php

namespace jubianchi\gossip;

use jubianchi\gossip\behaviors\writable;

interface writer
{
	public function write(writable $writable);
	public function writeString($string);
}