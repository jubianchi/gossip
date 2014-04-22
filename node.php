<?php

namespace jubianchi\gossip;


interface node
{
    public function __toString();

    public function tell(message $gossip);
    public function listen(node $to, message $gossip);
} 
