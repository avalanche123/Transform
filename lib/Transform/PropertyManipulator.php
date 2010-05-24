<?php
namespace Transform;

use ReflectionObject,
    ReflectionProperty;

class PropertyManipulator {
    /**
     * @var int $filter
     */
    protected $filter;
    /**
     * @param int $filter
     * @see ReflectionClass::getProperties
     */
    public function __construct($filter = null) {
        if ( ! isset ($filter)) {
            $filter =    ReflectionProperty::IS_PUBLIC
                        | ReflectionProperty::IS_PROTECTED
                        | ReflectionProperty::IS_PRIVATE;
        }
        $this->filter = $filter;
    }
    /**
     * @param mixed $object
     * @param array $values
     * @return mixed $object
     */
    public function inject($object, array $values) {
        $object = $this->getObject($object);
        $class = $this->getReflection($object);
        foreach ($class->getProperties($this->filter) as $property) {
            $this->ensureAccessible($property);
            if (isset ($values[$property->getName()])) {
                $property->setValue($object, $values[$property->getName()]);
            }
        }
        return $object;
    }
    /**
     * @param mixed $object
     * @return array $data
     */
    public function extract($object) {
        $object = $this->getObject($object);
        $class = $this->getReflection($object);
        $data = array();
        foreach ($class->getProperties($this->filter) as $property) {
            $this->ensureAccessible($property);
            $data[$property->getName()] = $property->getValue($object);
        }
        return $data;
    }
    /**
     * @return int $filter
     */
    public function getPropertyFilter() {
        return $this->filter;
    }
    
    private function getReflection($object) {
        static $reflections;
        if ( ! isset ($reflections)) {
            $reflections = array();
        }
        $className = get_class($object);
        if ( ! isset ($reflections[$className])) {
            $reflections[$className] = new ReflectionObject($object);
        }
        return $reflections[$className];
    }
    
    private function getObject($object) {
        if ( ! is_object($object) && ! class_exists($object)) {
            throw new \InvalidArgumentException('Class ' . (string) $object . 'doesn\'t exist');
        }
        return is_object($object) ? $object : new $object;
    }
    
    private function ensureAccessible(ReflectionProperty $property) {
        if ($property->isPrivate() || $property->isProtected()) {
            $property->setAccessible(true);
        }
    }

}