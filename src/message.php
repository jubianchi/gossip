<?php

namespace jubianchi\gossip;


interface message
{
    public function tell(node $to, node $from = null);
} 
