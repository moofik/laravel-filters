<?php

namespace Moofik\LaravelFilters\Utils;


class TraitFinder
{
    /**
     * @param $class
     * @param bool $autoload
     * @return array
     */
    function classUsesDeep($class, bool $autoload = true): array
    {
        $traits = [];

        do {
            $traits = array_merge(class_uses($class, $autoload), $traits);
        } while ($class = get_parent_class($class));

        $traitsToSearch = $traits;
        while (!empty($traitsToSearch)) {
            $newTraits = class_uses(array_pop($traitsToSearch), $autoload);
            $traits = array_merge($newTraits, $traits);
            $traitsToSearch = array_merge($newTraits, $traitsToSearch);
        };

        foreach ($traits as $index => $name) {
            $traits = array_merge(class_uses($name, $autoload), $traits);
        }

        return array_unique($traits);
    }

    /**
     * @param $class
     * @param string $trait
     * @return bool
     */
    public function isTraitUsed($class, string $trait): bool
    {
        $usedTraits = $this->classUsesDeep($class, $trait);

        return in_array($trait, $usedTraits);
    }
}
