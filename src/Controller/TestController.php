<?php


namespace MisterIcy\RnR\Controller;


use MisterIcy\RnR\Response;

class TestController extends AbstractRestController
{

    public function handle(): Response
    {
        if (!$this->validateRequest()) {
            die("Invalid Request");
        };
        return new Response(200, ["message" => "JWT is valid"]);
    }
}
