<?php declare(strict_types=1);


namespace MisterIcy\RnR\Controller;

use MisterIcy\RnR\Response;
use MisterIcy\RnR\RestAnnotation;

/**
 * Test controller.
 *
 * @package MisterIcy\RnR\Controller
 */
final class VersionController extends AbstractRestController
{
    /**
     * Prints the system's version and a status.
     *
     * @return \MisterIcy\RnR\Response
     * @RestAnnotation(method="GET", uri="", anonymous=true, protected=false, admin=false)
     */
    public function getVersion(): Response
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
