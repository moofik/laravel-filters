<?php

namespace Moofik\LaravelFilters\Commands;


use Illuminate\Console\Command;
use Moofik\LaravelFilters\Repository\ModelDiscoverer;

class InstallCommand extends Command
{
    protected $signature = 'filter:install';

    protected $description = 'Install all filter extension requirements';

    /**
     * @var ModelDiscoverer
     */
    private $discoverer;

    /**
     * InstallCommand constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->discoverer = new ModelDiscoverer();
    }

    public function handle()
    {
        $this->discoverer->scanAndCache();
    }
}
