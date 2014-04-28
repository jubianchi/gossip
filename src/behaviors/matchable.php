<?php

namespace jubianchi\gossip\behaviors;


interface matchable
{
	public function ifMatch($pattern, callable $callback);
	public function ifNotMatch($pattern, callable $callback);
}