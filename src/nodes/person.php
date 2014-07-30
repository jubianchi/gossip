<?php

namespace jubianchi\gossip\nodes;

use jubianchi\gossip\node;
use jubianchi\gossip\action;
use jubianchi\gossip\message;
use jubianchi\gossip\behaviors;
use jubianchi\gossip\writer;

class person implements node, behaviors\writable
{
    protected $name;
    protected $friends;
    protected $actions;

    public function __construct($name, node\collection $friends = null, action\collection $actions = null)
    {
        $this->name = $name;
        $this->friends = $friends ?: new node\collection();
        $this->actions = $actions ?: new action\collection();
    }

    public function writeTo(writer $writer)
    {
		$writer->writeString($this->name);

		return $this;
    }

	public function writeFriendsTo(writer $writer)
	{
		$this->friends->writeTo($writer);

		return $this;
	}

    public function link(node $to)
    {
		$this->friends->ifNotFound($to, function(node $item) { $this->friends->add($item); });

        return $this;
    }

    public function unlink(node $from)
    {
        $this->friends->ifFound($from, function(node $item) { $this->friends->remove($item); });

        return $this;
    }

    public function tell(message $gossip)
    {
		$this->friends->walk(function(node $friend) use ($gossip) { $gossip->tell($friend, $this); });

        return $this;
    }

    public function listen(node $to, message $gossip)
    {
		$this->actions->forEachMatch($gossip, function(action $action) use ($gossip, $to) {
				$gossip->run($action, $to, $this);
			}
		);

		$this->link($to)->tell($gossip);

        return $this;
    }

    public function on(action $action)
    {
        $this->actions->add($action);

        return $this;
    }

    public function off(action $action)
    {
        $this->actions->remove($action);

        return $this;
    }
} 
