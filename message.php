<?php

namespace jubianchi\gossip;


interface message
{
    public function __toString();

    public function tell(node $to, node $from = null);
} 
