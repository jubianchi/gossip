<?php

namespace jubianchi\gossip\messages;

use jubianchi\gossip\action;
use jubianchi\gossip\behaviors;
use jubianchi\gossip\node;
use jubianchi\gossip\message;
use jubianchi\gossip\writer;

class gossip implements message, behaviors\writable, behaviors\matchable
{
    protected $message;
    protected $sources;

    public function __construct($message, node $source)
    {
        $this->message = $message;
        $this->sources = (new node\collection())->add($source);
    }

    public function writeTo(writer $writer)
    {
		$writer->writeString($this->message);

		return $this;
    }

	public function writeSourcesTo(writer $writer)
	{
		$this->sources->writeTo($writer);

		return $this;
	}

    public function tell(node $to, node $from = null)
    {
        $from = $from ?: $this->sources->last();

		$this->sources
			->ifNotFound($from, function(node $from) { $this->sources->add($from); })
			->ifNotFound($to, function(node $to) use ($from) { $to->listen($from, $this); })
		;

        return $this;
    }

    public function run(action $action, node $from, node $to)
    {
		$action->run($this, $from, $to, $this->sources->first());

        return $this;
    }

    public function addFriend(node $target, node $friend = null)
    {
        $friend = $friend ?: $this->sources->last();

        $target->link($friend);
		$friend->link($target);

        return $this;
    }

	public function ifMatch($pattern, callable $callback)
	{
		if (preg_match($pattern, $this->message) > 0) {
			$callback($this);
		}

		return $this;
	}

	public function ifNotMatch($pattern, callable $callback)
	{
		if (preg_match($pattern, $this->message) === 0) {
			$callback($this);
		}

		return $this;
	}
} 
