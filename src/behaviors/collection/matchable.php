<?php

namespace jubianchi\gossip\behaviors\collection;


interface matchable
{
	public function forEachMatch($pattern, callable $callback);
	public function forEachNotMatch($pattern, callable $callback);
}