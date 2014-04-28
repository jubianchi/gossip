<?php

namespace jubianchi\gossip;


interface node
{
    public function tell(message $gossip);
    public function listen(node $to, message $gossip);
	public function link(node $to);
} 
