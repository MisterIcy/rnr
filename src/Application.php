<?php


namespace MisterIcy\RnR;


use MisterIcy\RnR\Controller\ControllerInterface;
use MisterIcy\RnR\Controller\SecurityController;
use MisterIcy\RnR\Controller\TestController;
use MisterIcy\RnR\Controller\UserController;
use MisterIcy\RnR\Controller\VersionController;
use MisterIcy\RnR\Exceptions\NotFoundException;

class Application
{

    private ControllerInterface $instance;

    public function __construct()
    {
        $this->instance = $this->getAction();
    }

    public function getAction(): ControllerInterface
    {
        if ($_SERVER['REQUEST_URI'] === '/') {
            return new VersionController();
        }

        $uriElements = explode('/', $_SERVER['REQUEST_URI']);
        // First element of the array is the action, the rest (here only one) are the parameters
        switch ($uriElements[1]) {
            case 'login':
                return new SecurityController();
            case 'test':
                return new TestController();
            case 'user':
                return new UserController();
        }

        throw new NotFoundException("{$_SERVER['REQUEST_URI']} was not found");


    }

    public function run() : Response
    {
        return $this->instance->handle();
    }

}

