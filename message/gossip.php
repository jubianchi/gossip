<?php

namespace jubianchi\gossip\message;

use jubianchi\gossip\node;
use jubianchi\gossip\message;

class gossip implements message
{
    protected $message;
    protected $sources;

    public function __construct($message, node $source)
    {
        $this->message = $message;
        $this->sources = [$source];
    }

    public function __toString()
    {
        return $this->message;
    }

    public function tell(node $to, node $from = null)
    {
        $from = $from ?: end($this->sources);

        if (false === in_array($from, $this->sources)) {
            $this->sources[] = $from;
        }

        if (false === $this->isAware($to)) {
            $to->listen($from, $this);
        }

        return $this;
    }

    public function handle(array $actions, node $to, node $from)
    {
        foreach ($actions as $action) {
            $action->handle($this, $to, $from, reset($this->sources));
        }

        return $this;
    }

    public function isAware(node $people)
    {
        return in_array($people, $this->sources);
    }

    public function addFriend(node $target, node $friend = null)
    {
        $friend = $friend ?: end($this->sources);

        $target->addFriend($friend);

        return $this;
    }

    function serialize()
    {
        return array(
            'message' => $this->message,
            'from' => reset($this->sources)->serialize(),
            'sources' => array_map(function($friend) { return $friend->serialize(); }, $this->sources)
        );
    }
} 
