<?php

namespace mpba\Modules\Activators;

use App\Models\ModuleStatus;
use Illuminate\Cache\CacheManager;
use Illuminate\Container\Container;
use mpba\Modules\Contracts\ActivatorInterface;
use mpba\Modules\Module;


class DatabaseActivator implements ActivatorInterface
{
    protected CacheManager $cache;
    protected string $cacheKey;
    protected int $cacheLifetime;

    protected array $modulesStatuses = [];

    public function __construct(Container $app)
    {
        $this->cache = $app['cache'];

        $this->cacheKey = config('modules.activators.file.cache-key', 'modules');
        $this->cacheLifetime = config('modules.activators.file.cache-lifetime', 60);

        $this->modulesStatuses = $this->getModulesStatuses();
    }

    public function reset(): void
    {
        ModuleStatus::query()->delete();
        $this->modulesStatuses = [];
        $this->flushCache();
    }

    public function enable(Module $module): void
    {
        $this->setActive($module, true);
    }

    public function disable(Module $module): void
    {
        $this->setActive($module, false);
    }

    public function hasStatus(Module $module, bool $status): bool
    {
        $name = $module->getName();

        if (!isset($this->modulesStatuses[$name])) {
            return $status === false;
        }

        return $this->modulesStatuses[$name] === $status;
    }

    public function setActive(Module $module, bool $active): void
    {
        $this->setActiveByName($module->getName(), $active);
    }

    public function setActiveByName(string $name, bool $status): void
    {
        ModuleStatus::updateOrCreate(
            ['name' => $name],
            ['enabled' => $status]
        );

        $this->modulesStatuses[$name] = $status;

        $this->flushCache();
    }

    public function delete(Module $module): void
    {
        ModuleStatus::where('name', $module->getName())->delete();

        unset($this->modulesStatuses[$module->getName()]);

        $this->flushCache();
    }

    protected function getModulesStatuses(): array
    {
        if (!config('modules.cache.enabled')) {
            return $this->readFromDatabase();
        }

        return $this->cache->remember(
            $this->cacheKey,
            $this->cacheLifetime,
            fn() => $this->readFromDatabase()
        );
    }

    protected function readFromDatabase(): array
    {
        return ModuleStatus::query()
            ->pluck('enabled', 'name')
            ->map(fn($value) => (bool)$value)
            ->toArray();
    }

    protected function flushCache(): void
    {
        $this->cache->forget($this->cacheKey);
    }

    public function setProtected(string $name, bool $protected): void
    {
        ModuleStatus::updateOrCreate(
            ['name' => $name],
            ['protected' => $protected]
        );

        $this->flushCache();
    }

}
