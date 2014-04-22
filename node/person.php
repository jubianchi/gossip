<?php

namespace jubianchi\gossip\node;

use jubianchi\gossip\node;
use jubianchi\gossip\message;

class person implements node
{
    protected $name;
    protected $friends;
    protected $actions = array();

    public function __construct($name, array $friends = array())
    {
        $this->name = $name;
        $this->friends = $friends;
    }

    public function __toString()
    {
        return $this->name;
    }

    public function addFriend(node $friend)
    {
        if (false === in_array($friend, $this->friends)) {
            $this->friends[] = $friend->addFriend($this);
        }

        return $this;
    }

    public function tell(message $gossip)
    {
        foreach ($this->friends as $friend) {
            $gossip->tell($friend, $this);
        }

        return $this;
    }

    public function listen(node $to, message $gossip)
    {
        $this->addFriend($to);

        $this->tell($gossip->handle($this->actions, $this, $to));

        return $this;
    }

    /*public function on(action $action)
    {
        $this->actions[] = $action;

        return $this;
    }*/

    function serialize()
    {
        return array(
            'name' => $this->name,
            'friends' => array_map(function($friend) { return $friend->name; }, $this->friends)
        );
    }
} 
