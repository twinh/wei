<?php

namespace Wei;

/**
 * The class util service
 */
class Cls extends Base
{
    /**
     * Return the class name without namespace
     *
     * @param string|object $class
     * @return string
     * @svc
     */
    public static function baseName($class): string
    {
        if (is_object($class)) {
            $class = get_class($class);
        }
        $parts = explode('\\', $class);
        return end($parts);
    }

    /**
     * Return the traits used by the given class, including those used by all parent classes and other traits
     *
     * @param string|object $class
     * @param bool $autoload
     * @return array
     * @see https://www.php.net/manual/en/function.class-uses.php#112671
     * @svc
     */
    protected function usesDeep($class, bool $autoload = true): array
    {
        $traits = [];

        // Get traits of all parent classes
        do {
            $traits = array_merge(class_uses($class, $autoload), $traits);
        } while ($class = get_parent_class($class));

        // Get traits of all parent traits
        $traitsToSearch = $traits;
        while (!empty($traitsToSearch)) {
            $newTraits = class_uses(array_pop($traitsToSearch), $autoload);
            $traits = array_merge($newTraits, $traits);
            $traitsToSearch = array_merge($newTraits, $traitsToSearch);
        }

        foreach ($traits as $trait => $same) {
            $traits = array_merge(class_uses($trait, $autoload), $traits);
        }

        return array_unique($traits);
    }
}
