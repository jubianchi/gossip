<?php

namespace jubianchi\gossip\tests\functionnal;


use jubianchi\gossip\action\collection;
use jubianchi\gossip\action;
use jubianchi\gossip\actions;
use jubianchi\gossip\condition;
use jubianchi\gossip\messages\gossip;
use jubianchi\gossip\nodes\person;
use jubianchi\gossip\writable\decorator;
use jubianchi\gossip\writable\decorators;
use jubianchi\gossip\writable\raw;
use jubianchi\gossip\writers\buffer;
use jubianchi\gossip\writers\stdout;

class board
{
    protected $actions;
    protected $debug;

    public function __construct(collection $actions = null, condition $debug = null)
    {
        $this->actions = $actions ?: new action\collection();
        $this->debug = $debug ?: new condition(function() {
                return isset($_SERVER['argv'][1]) &&  $_SERVER['argv'][1] === '--debug';
            }
        );

        $writer = new stdout();

        $sendPrompt = (new decorator\collection())
            ->add(new decorators\cell(10, decorators\cell::RIGHT))
            ->add(new decorators\color(decorators\color::GREEN))
            ->add(new decorators\append(' >'))
        ;

        $receivePrompt = (new decorator\collection())
            ->add(new decorators\cell(10, decorators\cell::RIGHT))
            ->add(new decorators\color(decorators\color::GREEN))
            ->add(new decorators\append(' <'))
        ;

        $debugPrompt = (new decorator\collection())
            ->add(new decorators\cell(10, decorators\cell::RIGHT))
            ->add(new decorators\color(decorators\color::PURPLE))
            ->add(new decorators\append(' |'))
        ;

        $name = (new decorator\collection())
            ->add(new decorators\color(decorators\color::GREEN))
            ->add(
                $padding = (new decorator\collection())
                    ->add($paddingLeft = new decorators\padding(1, ' '))
                    ->add(new decorators\padding(1, ' ', decorators\padding::RIGHT))
            )
        ;

        $noticeName = (new decorator\collection())
            ->add(new decorators\color(decorators\color::PURPLE, null, true))
            ->add(
                $padding = (new decorator\collection())
                    ->add($paddingLeft = new decorators\padding(1, ' '))
                    ->add(new decorators\padding(1, ' ', decorators\padding::RIGHT))
            )
        ;

        $message = (new decorator\collection())
            ->add(new decorators\color(decorators\color::YELLOW))
            ->add($padding)
        ;

        $this->actions = [
            new action(
                '/.+/',
                function(gossip $gossip, person $from, person $to, person $source) use ($writer, $receivePrompt, $name, $message) {
                    $writer
                        ->write($receivePrompt->decorate($to))
                        ->writeString(' Received')
                        ->write($message->decorate($gossip))
                        ->writeString('from')
                        ->write($name->decorate($from))
                        ->writeString('(originally from')
                        ->write($name->decorate($source))
                        ->writeString(')')
                        ->writeString(PHP_EOL)
                    ;
                }
            ),
            new actions\conditional(
                '/.+/',
                function(gossip $gossip, person $from, person $to, person $source) use ($writer, $debugPrompt, $paddingLeft) {
                    $buffer = new buffer();
                    $to->writeFriendsTo($buffer);

                    $writer
                        ->write($debugPrompt->decorate($to))
                        ->writeString(' My friends:')
                        ->write($paddingLeft->decorate($buffer))
                        ->writeString(PHP_EOL)
                    ;
                },
                $this->debug
            ),
            new action(
                '/join/',
                function(gossip $gossip, person $from, person $to, person $source) use ($writer, $debugPrompt, $sendPrompt, $name, $noticeName, $message) {
                    $writer
                        ->write($sendPrompt->decorate($to))
                        ->write($noticeName->decorate($source))
                        ->write((new decorators\color(decorators\color::PURPLE))->decorate(new raw('joined')))
                        ->writeString(PHP_EOL)
                    ;

                    $gossip->addFriend($to, $source);

                    $hello = new gossip('Hello', $to);

                    $writer
                        ->write($sendPrompt->decorate($to))
                        ->writeString(' Sending')
                        ->write($message->decorate($hello))
                        ->writeString('to')
                        ->write($name->decorate($source))
                        ->writeString(PHP_EOL)
                    ;

                    $source->listen($to, $hello);
                }
            )
        ];
    }

    public function attach(person $person)
    {
        foreach ($this->actions as $action) {
            $person->on($action);
        }

        return $this;
    }
} 
