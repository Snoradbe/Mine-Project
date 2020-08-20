<?php


namespace App\Services;


use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;

class ConfigSaver
{
    /**
     * Сохранить конфиг в файл.
     *
     * @param string $name
     * @param array $config
     */
    public function save(string $name, array $config): void
    {
        $file = base_path('config' . DIRECTORY_SEPARATOR . $name . '.php');

        file_put_contents(
            $file,
            '<?php ' . PHP_EOL . PHP_EOL . 'return ' . var_export($config, true) . ';' . PHP_EOL
        );

        config()->set($name, $config);
    }

    /**
     * Обновить кэша конфига.
     *
     * @return void
     * @throws \LogicException
     */
    public function updateCache(): void
    {
        $files = app(Filesystem::class);
        $configPath = app()->getCachedConfigPath();

        $files->delete($configPath);

        $files->put(
            $configPath, '<?php return '.var_export(config()->all(), true).';'.PHP_EOL
        );

        try {
            require $configPath;
        } catch (\Throwable $e) {
            $files->delete($configPath);

            throw new \LogicException('Your configuration files are not serializable.', 0, $e);
        }
    }

    /**
     * Переформатировать кэш всего конфига.
     */
    public function reCache(): void
    {
        Artisan::call('config:cache');
    }
}
