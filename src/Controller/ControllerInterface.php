<?php


namespace MisterIcy\RnR\Controller;


use MisterIcy\RnR\Response;

interface ControllerInterface
{
    public function handle() : Response;

}
