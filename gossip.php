<?php

class action
{
    protected $pattern;
    protected $callback;

    public function __construct($pattern, closure $callback)
    {
        $this->pattern = $pattern;
        $this->callback = $callback;
    }

    public function handle(gossip $gossip, people $to, people $teller, people $source)
    {
        $response = null;

        if (preg_match('/' . $this->pattern . '/', (string) $gossip)) {
            $response = call_user_func_array($this->callback->bindTo($to), array($gossip, $source));
        }

        if ($response instanceof self) {
            $response->tell($teller);
        }

        return $this;
    }
}

class gossip
{
    protected $message;
    protected $sources;

    public function __construct($message, people $source)
    {
        $this->message = $message;
        $this->sources = [$source];
    }

    public function __toString()
    {
        return $this->message;
    }

    public function tell(people $to, people $teller = null)
    {
        $teller = $teller ?: end($this->sources);

        if (false === in_array($teller, $this->sources)) {
            $this->sources[] = $teller;
        }

        if (false === $this->isAware($to)) {
            $to->listen($teller, $this);
        }

        return $this;
    }

    public function handle(array $actions, people $to, people $teller)
    {
        foreach ($actions as $action) {
            $action->handle($this, $to, $teller, reset($this->sources));
        }

        return $this;
    }

    public function isAware(people $people)
    {
        return in_array($people, $this->sources);
    }

    public function addFriend(people $target, people $friend = null)
    {
        $friend = $friend ?: end($this->sources);

        $target->addFriend($friend);
        $friend->addFriend($target);

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

class people
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

    public function addFriend(people $friend)
    {
        if (false === in_array($friend, $this->friends)) {
            $this->friends[] = $friend;
        }

        return $this;
    }

    public function tell(gossip $gossip)
    {
        foreach ($this->friends as $friend) {
            $gossip->tell($friend, $this);
        }

        return $this;
    }

    public function listen(people $teller, gossip $gossip)
    {
        $this->tell($gossip->handle($this->actions, $this, $teller));

        return $this;
    }

    public function on(action $action)
    {
        $this->actions[] = $action;

        return $this;
    }

    function serialize()
    {
        return array(
            'name' => $this->name,
            'friends' => array_map(function($friend) { return $friend->name; }, $this->friends)
        );
    }
}

$log = new action(
    '.+',
    function(gossip $gossip, people $source) {
        //printf(" \033[35m%-6s\033[0m | Received \033[33m'%s'\033[0m from \033[32m%s\033[0m" . PHP_EOL, $this, $gossip, $source);

        $serial = $gossip->serialize();

        //printf(" \033[35m%-6s\033[0m | From: %s" . PHP_EOL, '', json_encode($serial['from']));
        //printf(" \033[35m%-6s\033[0m | Teller: %s" . PHP_EOL, '', json_encode(end($serial['sources'])));
    }
);

$report = new action(
    '.+',
    function() {
        $target = $this->serialize();

        printf(" \033[35m%-6s\033[0m | My friends: %s" . PHP_EOL, $this, json_encode($target['friends']));
    }
);

$join = new action(
    'join',
    function(gossip $gossip, people $source) {
        printf(" \033[32m%-6s\033[0m > \033[32m%s\033[0m joined" . PHP_EOL, $this, $source);
        $gossip->addFriend($this, $source);

        $gossip = new gossip('Hello', $this);
        printf(" \033[32m%-6s\033[0m > Sending \033[33m'%s'\033[0m to \033[32m%s\033[0m" . PHP_EOL, $this, $gossip, $source);

        return $gossip;
    }
);

$john = (new people('john'))->on($log)->on($join)->on($report);
$david = (new people('david'))->on($log)->on($join)->on($report);
$rasmus = (new people('rasmus'))->on($log)->on($join)->on($report);
$jubianchi = (new people('jubianchi'))->on($log)->on($join)->on($report);

(new gossip('join', $david))->tell($john);
(new gossip('join', $rasmus))->tell($david);
(new gossip('join', $jubianchi))->tell($john);
