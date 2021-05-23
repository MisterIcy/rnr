<?php


namespace MisterIcy\RnR;


use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManager;
use Exception;
use MisterIcy\RnR\Exceptions\InternalServerErrorException;
use ReflectionClass;
use ReflectionException;
use ReflectionParameter;
use Symfony\Component\PropertyAccess\PropertyAccess;

final class Persistence
{
    private const DATE_FORMATS = [
        'Y-m-d\TH:i:s.uO',
        'Y-m-d\TH:i:sO',
        'Y-m-d\TH:i:s',
        'Y-m-d',
    ];


    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private EntityManager $entityManager;

    /**
     * Persistence constructor.
     * @param \Doctrine\ORM\EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Persists an object to database.
     *
     * This calls the data injector and persists the object into database
     *
     * @param object $object The object to be persisted
     * @param array<string|int, mixed> $data Data in array format, to be injected in the object
     * @return object The transformed object, after the completion of all operations
     * @throws \MisterIcy\RnR\Exceptions\InternalServerErrorException
     */
    final public function persist(
        object $object,
        array $data
    ): object {
        try {
            $object = $this->inject($object, $data);
            $this->entityManager->persist($object);
            $this->entityManager->flush();
        } catch (Exception $exception) {
            throw $exception;
        }

        return $object;
    }

    /**
     * Injects data into an object.
     *
     * @param object $object The object to inject data to
     * @param array<string|int, mixed> $data The data to be injected
     * @return object The updated object
     * @throws \MisterIcy\RnR\Exceptions\InternalServerErrorException|\ReflectionException When something goes wrong, an exception is thrown.
     */
    private function inject(object $object, array $data): object
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        // Since strict types are enabled, we do not need to worry about exceptions
        $reflector = new ReflectionClass(get_class($object));

        // But, if the object is not an Entity, throw an exception
        if (strpos($reflector->getName(), 'Entity') === false) {
            throw new InternalServerErrorException(
                sprintf("The supplied object of type %s is not an entity", $reflector->getName())
            );
        }

        // If the data supplied by the caller is empty, just throw an exception
        if (empty($data)) {
            throw new InternalServerErrorException(
                "No data were supplied for this operation"
            );
        }

        foreach ($data as $key => $datum) {
            //For every piece of data in the array
            //Check if there is a setter for the property named $key
            try {
                $method = $reflector->getMethod("set$key");
            } catch (ReflectionException $reflectionException) {
                //If there is no setter for this property, skip this iteration
                continue;
            }

            $methodParameters = $method->getParameters();
            // Setters must have only ONE parameter.
            if (empty($methodParameters) || count($methodParameters) > 1) {
                continue;
            }

            /** @var ReflectionParameter|null $paramType */
            $paramType = $methodParameters[0]->getType();

            // Initialize value
            $value = $datum;

            if (is_null($paramType)) {
                //The parameter does not have a type. Inject the data into the object and continue the loop.
                $propertyAccessor->setValue($object, $key, $value);
                continue;
            }

            $parameterName = $paramType->getName();
            $value = $this->getValueOfDatum($datum, $parameterName);
            // If the parameter cannot be null and the caller tries to inject a null value to it, just skip.
            if (!$paramType->allowsNull() && is_null($value)) {
                continue;
            }

            $propertyAccessor->setValue($object, $key, $value);
        }

        return $object;
    }

    /**
     * @param mixed $datum
     * @param string $parameterName
     * @return mixed
     * @throws \MisterIcy\RnR\Exceptions\InternalServerErrorException
     */
    private function getValueOfDatum($datum, string $parameterName)
    {
        if ($parameterName === 'int') {
            return intval($datum);
        } elseif ($parameterName === 'double') {
            return doubleval($datum);
        } elseif ($parameterName === 'float') {
            return floatval($datum);
        } elseif ($parameterName === 'string') {
            return strval($datum);
        } elseif ($parameterName === 'bool') {
            return boolval($datum);
        } elseif (strpos($parameterName, 'Entity') !== false) {
            if (isset($datum['id'])) {
                return $this->entityManager
                    ->getRepository($parameterName)
                    ->find($datum['id']);
            }
        } elseif ($parameterName === 'DateTime' || $parameterName === 'DateTimeInterface') {
            return $this->getDateTimeOfDatum($datum);
        } elseif ($parameterName === 'array') {
            //value is preset to datum. If this is not an array, just skip it
            if (!is_array($datum)) {
                return null;
            }
        } elseif ($parameterName === 'DateInterval') {
            // I can't imagine why one would like to store an Interval in database but...
            //@codeCoverageIgnoreStart
            // value is preset to datum. If this is not a DateInterval just skip it
            if (!($datum instanceof DateInterval)) {
                return null;
            }
            //@codeCoverageIgnoreEnd
        }

        return $datum;
    }

    /**
     * @param mixed $datum
     * @return DateTime|null
     * @throws \MisterIcy\RnR\Exceptions\InternalServerErrorException
     */
    public function getDateTimeOfDatum($datum): ?DateTime
    {
        if (isset($datum['date'])) {
            return $this->ISO8601ToDateTime($datum['date']);
        } elseif (!isset($datum['date']) && isset($datum['timestamp'])) {
            return (new DateTime())->setTimestamp($datum['timestamp']);
        } else {
            return null;
        }
    }

    /**
     * @param string $date
     * @return \DateTime|mixed
     * @throws \MisterIcy\RnR\Exceptions\InternalServerErrorException
     */
    private function ISO8601ToDateTime(string $date)
    {
        date_default_timezone_set('Europe/Athens');
        $dateObject = false;
        foreach (self::DATE_FORMATS as $dateFormat) {
            $dateObject = DateTime::createFromFormat($dateFormat, $date);
            if ($dateObject !== false) {
                break;
            }
        }

        if ($dateObject === false) {
            throw new InternalServerErrorException("Unknown format for date $date");
        }
        $dateObject->setTimezone(new \DateTimeZone('Europe/Athens'));

        return $dateObject;
    }

}
