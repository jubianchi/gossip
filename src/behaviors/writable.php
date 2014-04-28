<?php

namespace jubianchi\gossip\behaviors;

use jubianchi\gossip\writer;

interface writable
{
	public function writeTo(writer $writer);
} 