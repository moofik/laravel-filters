<?php

namespace Moofik\LaravelFilters\Repository;


use Illuminate\Filesystem\Filesystem;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileObject;
use UnexpectedValueException;

class ModelDiscoverer
{
    protected static $cacheFilename = '/laravel_filters_cache.php';

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * ModelDiscoverer constructor.
     */
    public function __construct()
    {
        $this->filesystem = new Filesystem();
    }

    public function scanAndCache()
    {
        $directory = base_path() . '/app';
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory),
            RecursiveIteratorIterator::CHILD_FIRST
        );
        try {
            $models = [];
            /**
             * @var SplFileObject $file
             */
            foreach ($files as $filename => $file) {
                $tokens = token_get_all(file_get_contents($filename));
                $isExtendsModel = null;
                $fileNamespace = null;
                $className = null;

                foreach ($tokens as $index => $token) {
                    if (isset($token[1]) && $token[1] === 'extends') {
                        $isExtendsModel = $this->isExtendsModel($index, $tokens);
                    } elseif (isset($token[1]) && $token[1] === 'namespace') {
                        $fileNamespace = $this->extractFileNamespace($index, $tokens);
                    } elseif (isset($token[1]) && $token[1] === 'class') {
                        $className = $this->extractClassName($index, $tokens);
                    }

                    if ($isExtendsModel && strlen($fileNamespace) > 0 && strlen($className) > 0) {
                        $models[$className] = $fileNamespace . '\\' . $className;
                        $isExtendsModel = null;
                        $fileNamespace = null;
                        $className = null;
                    }
                }
            }

            if ($this->filesystem->exists(self::getCacheFilePath())) {
                $this->filesystem->delete(self::getCacheFilePath());
            }

            $this->filesystem->put(self::getCacheFilePath(), json_encode($models));
        } catch (UnexpectedValueException $e) {
            printf("Directory [%s] contained a directory we can not recurse into", $directory);
        }
    }

    /**
     * @param int $index
     * @param array $tokens
     * @return string
     */
    private function extractFileNamespace(int $index, array $tokens): string
    {
        $namespace = '';

        for ($i = $index + 1; $i < count($tokens); $i++) {
            if ($tokens[$i] === ";") {
                break;
            }

            $namespace .= isset($tokens[$i][1]) ? $tokens[$i][1] : '';
        }

        return str_replace(' ', '', $namespace);
    }

    /**
     * @param int $index
     * @param array $tokens
     * @return bool
     */
    private function isExtendsModel(int $index, array $tokens): bool
    {
        for ($i = $index + 1; $i < count($tokens); $i++) {
            $token = $tokens[$i];

            if (isset($token[1]) && $token[1] !== ' ' && $token[1] !== 'Model' && $token[1] !== 'Authenticatable') {
                return false;
            }

            if (isset($token[1]) && ($token[1] === 'Model' || $token[1] === 'Authenticatable')) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param int $index
     * @param array $tokens
     * @return string
     */
    private function extractClassName(int $index, array $tokens): string
    {
        $class = '';

        for ($i = $index + 1; $i < count($tokens); $i++) {
            if (isset($tokens[$i][1]) && $tokens[$i][1] === "extends") {
                break;
            }

            $class .= isset($tokens[$i][1]) ? $tokens[$i][1] : '';
        }

        return str_replace(' ', '', $class);
    }

    /**
     * @return string
     */
    public static function getCacheFilePath(): string
    {
        return storage_path() . self::$cacheFilename;
    }
}
