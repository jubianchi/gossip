<?php

namespace jubianchi\gossip\behaviors;


interface searchable
{
	public function ifFound($mixed, callable $callback);
	public function ifNotFound($mixed, callable $callback);
}