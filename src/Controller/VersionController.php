<?php
declare(strict_types=1);


namespace MisterIcy\RnR\Controller;


use MisterIcy\RnR\Response;

class VersionController implements ControllerInterface
{
    public function handle(): Response
    {
        return new Response(
            Response::HTTP_OK,
            [
                'version' => '1.0',
                'status' => 'Sine sole sileo',
            ]
        );
    }
}
