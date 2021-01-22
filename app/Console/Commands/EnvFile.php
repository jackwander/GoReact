<?php

namespace App\Console\Commands;

use App\Console\Commands\Traits\Filesystem;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

/**
 * Class to update the .env file.
 */
class EnvFile
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * EnvFile constructor.
     *
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * Saves the config file.
     *
     * @param array $input
     *
     * @throws FileNotFoundException
     * @return bool
     * @fixme: update this to quote the values
     */
    public function save(array $input): bool
    {
        $path   = base_path('.env');
        $config = $this->filesystem->get($path);

        foreach ($input as $section => $data) {
            foreach ($data as $key => $value) {
                $env = strtoupper($section . '_' . $key);

                $config = preg_replace('/' . $env . '=(.*)/', $env . '=' . $value, $config);
            }
        }

        // Remove comments
        $config = preg_replace('/#(.*)[\n]/', '', $config);
        $config = preg_replace('/[\n]{3,}/m', PHP_EOL . PHP_EOL, $config);

        return (bool) $this->filesystem->put($path, trim($config) . PHP_EOL);
    }

    /**
     * Checks for new configuration values in .env.example and copy them to .env.
     *
     * @throws FileNotFoundException
     * @return bool
     * @fixme: update this to quote the values
     */
    public function update(): bool
    {
        $prev     = base_path('.env.prev');
        $current  = base_path('.env');
        $dist     = base_path('.env.example');

        $config = [];

        // Read the current config values into an array for the writeEnvFile method
        $content = $this->filesystem->get($current);
        foreach (explode(PHP_EOL, $content) as $line) {
            $line = trim($line);

            if (empty($line)) {
                continue;
            }

            $parts = explode('=', $line);

            if (count($parts) < 2) {
                continue;
            }

            $env   = strtolower($parts[0]);
            $value = trim($parts[1]);

            $section = substr($env, 0, strpos($env, '_'));
            $key     = substr($env, strpos($env, '_') + 1);

            $config[$section][$key] = $value;
        }

        // Backup the .env file, just in case it failed because we don't want to lose APP_KEY
        $this->filesystem->copy($current, $prev);

        // Copy the example file so that new values are copied
        $this->filesystem->copy($dist, $current);

        $result = $this->save($config);

        // If the updated .env is the same as the backup remove the backup
        if ($this->filesystem->md5($current) === $this->filesystem->md5($prev)) {
            $this->filesystem->delete($prev);
        }

        return $result;
    }
}