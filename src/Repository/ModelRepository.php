<?php


namespace Moofik\LaravelFilters\Repository;


use Illuminate\Filesystem\Filesystem;

class ModelRepository
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var ModelDiscoverer
     */
    private $discoverer;

    /**
     * ModelRepository constructor.
     * @param Filesystem $filesystem
     * @param ModelDiscoverer $discoverer
     */
    public function __construct(Filesystem $filesystem, ModelDiscoverer $discoverer)
    {
        $this->filesystem = $filesystem;
        $this->discoverer = $discoverer;
    }

    /**
     * @param string $shorthand
     * @return string|null
     */
    public function find(string $shorthand): ?string
    {
        try {
            if (!file_exists(ModelDiscoverer::getCacheFilePath())) {
                $this->discoverer->scanAndCache();
            }

            $models = json_decode($this->filesystem->get(storage_path() . ModelDiscoverer::getCacheFilePath()), true);

            return isset($models[$shorthand]) ? $models[$shorthand] : null;
        } catch (\Throwable $e) {
            return null;
        }
    }
}
