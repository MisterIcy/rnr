<?php
declare(strict_types=1);

namespace MisterIcy\RnR;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Error;
use MisterIcy\RnR\Exceptions\ForbiddenException;
use MisterIcy\RnR\Exceptions\NotFoundException;
use ReflectionClass;

/**
 * Class Application
 * @package MisterIcy\RnR
 */
final class Application
{
    /**
     * Extreme ways to save extreme times.
     *
     * @return \MisterIcy\RnR\Response
     * @throws \MisterIcy\RnR\Exceptions\ForbiddenException
     * @throws \MisterIcy\RnR\Exceptions\InternalServerErrorException
     * @throws \MisterIcy\RnR\Exceptions\NotFoundException
     * @throws \MisterIcy\RnR\Exceptions\UnauthorizedException
     * @throws \ReflectionException
     */
    public function run(): Response
    {
        $temp = [];
        $request = new Request();

        // Load all Controllers
        foreach (glob(__DIR__.'/Controller/*.php') as $controller) {
            require_once($controller);
        }
        AnnotationRegistry::registerLoader('class_exists');

        // Foreach declared class...
        foreach (get_declared_classes() as $class) {
            // If this is not a controller, just skip it
            if (!str_contains($class, 'Controller')) {
                continue;
            }

            try {
                //Create a reflector to get methods and annotations
                $reflector = new ReflectionClass(new $class);
            } catch (Error $error) {
                //You cannot instantiate Abstract Classes!
                continue;
            }
            // Check controller methods.
            foreach ($reflector->getMethods() as $method) {
                $reader = new AnnotationReader();
                /** @var \MisterIcy\RnR\RestAnnotation|null $restAnnotation */
                $restAnnotation = $reader->
                getMethodAnnotation($method, RestAnnotation::class);

                //If the method is not annotated, skip it.
                if (is_null($restAnnotation)) {
                    continue;
                }
                // This is not the method you are looking for...
                if ($restAnnotation->method !== $request->getMethod()) {
                    continue;
                }
                $methodUriParts = explode('/', $restAnnotation->uri);
                // For simplicity, the first URI part is always the action, the rest are the method arguments
                $action = array_shift($methodUriParts);
                $temp[] = $action;
                if ($action !== $request->getUriParts()[0]) {
                    continue;
                }
                // Security checks
                $security = new Security();

                if ($restAnnotation->protected && !$security->validateLogin()) {
                    throw new ForbiddenException();
                }
                if ($restAnnotation->admin && !$security->isAdmin()) {
                    throw new ForbiddenException();
                }
                // Due to above simplifications, remove the first element of the
                // request part - the action - and pass the rest as arguments
                $arguments = $request->getUriParts();
                array_shift($arguments);

                // Further simplification: All arguments are of type int
                return $method->invokeArgs(new $class, $arguments);
            }
        }

        throw new NotFoundException("{$request->getUri()} was not found");
    }
}

