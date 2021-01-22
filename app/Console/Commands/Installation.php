<?php

namespace App\Console\Commands;

use App\Console\Commands\EnvFile;
use App\Console\Commands\Traits\AskAndValidate;
use App\Console\Commands\Traits\GetAvailableOptions;
use App\Console\Commands\Traits\OutputStyles;
use App\Console\Commands\Traits\ProcessBuilder;
use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use RuntimeException;

class Installation extends Command
{
  use AskAndValidate, OutputStyles, GetAvailableOptions;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'goreact:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Application';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
      ConfigRepository $config,
      Filesystem $filesystem,
      EnvFile $writer,
      ProcessBuilder $builder,
      ValidationFactory $validator
    ) {
      parent::__construct();

      $this->config         = $config;
      $this->filesystem     = $filesystem;
      $this->builder     = $builder;
      $this->validator     = $validator;
      $this->writer     = $writer;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(EnvFile $writer)
    {
      $this->line('');

      $config = base_path('.env');

      if (!$this->filesystem->exists($config)) {
        $this->filesystem->copy(base_path('.env.example'), $config);
        $this->config->set('app.key', 'SomeRandomString');
      }
      $this->clearCaches();

      $this->block(' -- Welcome to GoReact -- ', 'fg=white;bg=green;options=bold');
      $this->line('');

      $this->line('Please answer the following questions:');
      $this->line('');
      $config = $this->restructureConfig([
          'db'      => $this->getDatabaseInformation(),
          'aws'    => $this->getAwsInformation(),
      ]);

      $user = $this->getAdminInformation();
      $this->info('Writing configuration file');
      $writer->save($config);

      $this->generateKey();

      $this->migrate();
      $this->createUser($user['name'], $user['email'], $user['password']);

      $this->clearCaches();
      $this->optimize();

      $this->line('');
      $this->line('');

      $this->block('Success! GoReact is now installed', 'fg=black;bg=green');
      $this->block('You can now run php artisan serve', 'fg=black;bg=green');
      $this->line('');
    }

    protected function runApp(): void
    {
        $this->info('Will try to run the application');
        $this->line('');

        echo $process = Artisan::call('serve -v');

        $this->line('');
    }

    /**
     * Clears all Laravel caches.
     */
    protected function clearCaches(): void
    {
        $this->callSilent('clear-compiled');
        $this->callSilent('cache:clear');
        $this->callSilent('route:clear');
        $this->callSilent('config:clear');
        $this->callSilent('view:clear');
    }

    /**
     * Runs the artisan optimize commands.
     */
    protected function optimize(): void
    {
        if (!$this->laravel->environment('local')) {
            $this->call('config:cache');
            $this->call('route:cache');
        }
    }

    /**
     * Change the configuration array generated by the prompt so it matches the config file.
     *
     * @param array $config
     *
     * @return array
     */
    private function restructureConfig(array $config): array
    {
        ksort($config);

        return $config;
    }

    /**
     * Calls the artisan key:generate to set the APP_KEY.
     */
    private function generateKey(): void
    {
        $this->info('Generating application key');
        $this->callSilent('key:generate', ['--force' => true]);
    }

    /**
     * Calls the artisan migrate to set up the database.
     */
    protected function migrate(): void
    {
        $this->info('Running database migrations');
        $this->line('');

        $process = Artisan::call('migrate:fresh');

        $this->line('');
    }

    /**
     * Forks a process to create the admin user.
     *
     * @param string $name
     * @param string $email
     * @param string $password
     */
    private function createUser(string $name, string $email, string $password): void
    {
        $process = Artisan::call('goreact:create-user',['name'=>$name, 'email'=>$email, 'password'=>$password]);
    }

    /**
     * Prompts the user for the database connection details.
     *
     * @return array
     */
    private function getDatabaseInformation(): array
    {
        $this->header('Database details');

        $connectionVerified = false;

        $database = [];
        while (!$connectionVerified) {
            // Should we just skip this step if only one driver is available?
            $type = $this->choice('Type', $this->getDatabaseDrivers(), 0);

            $database['connection'] = $type;

            if ($type !== 'sqlite') {
                $defaultPort = $type === 'mysql' ? 3306 : 5432;

                $host = $this->anticipate('Host', ['localhost'], 'localhost');
                $port = $this->anticipate('Port', [$defaultPort], $defaultPort);
                $name = $this->anticipate('Name', ['goreact'], 'goreact');
                $user = $this->ask('Username', 'root');
                $pass = $this->secret('Password');

                $database['host']     = $host;
                $database['port']     = $port;
                $database['database'] = $name;
                $database['username'] = $user;
                $database['password'] = $pass;
            }

            $connectionVerified = true;

            //$connectionVerified = $this->verifyDatabaseDetails($database);
        }

        return $database;
    }

    /**
     * Prompts the user for the AWS S3 details.
     *
     * @return array
     */
    private function getAwsInformation(): array
    {
        $this->header('AWS S3 details');

        $aws['access_key_id']       = $this->ask('Access Key ID');
        $aws['secret_access_key']   = $this->askSecretAndValidate('Secret Access Key', [], function ($answer) {
            return $answer;
        });
        $aws['default_region']      = $this->ask('Default Region');
        $aws['bucket']              = $this->ask('Bucket');

        return $aws;
    }

    private function getAdminInformation(): array
    {
        $this->header('User details');

        $name = $this->ask('Name', 'User');

        $email_address = $this->askAndValidate('Email address', [], function ($answer) {
            $validator = $this->validator->make(['email_address' => $answer], [
                'email_address' => 'email',
            ]);

            if (!$validator->passes()) {
                throw new RuntimeException($validator->errors()->first('email_address'));
            }

            return $answer;
        });

        $password = $this->askSecretAndValidate('Password', [], function ($answer) {
            $validator = $this->validator->make(['password' => $answer], [
                'password' => 'min:8',
            ]);

            if (!$validator->passes()) {
                throw new RuntimeException($validator->errors()->first('password'));
            }

            return $answer;
        });

        return [
            'name'     => $name,
            'email'    => $email_address,
            'password' => $password,
        ];
    }

    /**
     * Generates a Symfony Process instance for an artisan command.
     *
     * @param string $command
     * @param array  $args
     *
     * @return Process
     */
    private function artisanProcess(string $command, array $args = []): Process
    {
        $arguments = array_merge([
            base_path('artisan'),
            $command,
        ], $args, ['--ansi']);

        $this->builder->setPrefix('php');

        return $this->builder->setArguments($arguments)
                             ->setWorkingDirectory(base_path())
                             ->setTimeout(null)
                             ->getProcess();
    }

}
