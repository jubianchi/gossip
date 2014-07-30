<?php

namespace jubianchi\gossip\messages;

use jubianchi\gossip\action;
use jubianchi\gossip\behaviors;
use jubianchi\gossip\node;
use jubianchi\gossip\message;
use jubianchi\gossip\writer;

class priv extends gossip
{
    protected $message;
    protected $sources;

    public function __construct($message, node $source, node $recipient)
    {
        $this->message = $message;
        $this->source = $source;
        $this->recipient = $recipient;
    }

    public function writeTo(writer $writer)
    {
		$writer->writeString($this->message);

		return $this;
    }

    public function tell(node $to, node $from = null)
    {
        static $tell;

        if (null === $tell) {
            $tell = function(node $to, node $from = null) {
                $from = $from ?: $this->source;

                $to->listen($from, $this);
            };
        }

        if ($to === $this->recipient) {
            $tell($to, $from);

            $tell = function() {};
        }

        return $this;
    }

    public function run(action $action, node $from, node $to)
    {
		$action->run($this, $from, $to, $this->source);

        return $this;
    }

    public function addFriend(node $target, node $friend = null)
    {
        $friend = $friend ?: $this->source;

        $target->link($friend);
		$friend->link($target);

        return $this;
    }
} 
