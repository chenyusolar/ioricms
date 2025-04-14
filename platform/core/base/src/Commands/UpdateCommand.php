<?php

namespace Botble\Base\Commands;

use Botble\Base\Events\UpdatedEvent;
use Botble\Base\Events\UpdatingEvent;
use Botble\Base\Facades\BaseHelper;
use Botble\Base\Supports\Core;
use Illuminate\Console\Command;
use Illuminate\Support\Composer;

use function Laravel\Prompts\{confirm, note, progress, select};

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Process\Process;
use Throwable;

#[AsCommand('cms:update', 'Update system to latest version')]
class UpdateCommand extends Command
{
    public function __construct(protected Core $core, protected Composer $composer)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        BaseHelper::maximumExecutionTimeAndMemoryLimit();
        return self::SUCCESS;
    }

    protected function performUpdate(string $updateId, string $version): int
    {
        event(new UpdatingEvent());

        $progress = progress(
            label: 'Verifying license...',
            steps: 6,
        );

        $progress->start();

        try {
            // if (! $this->core->verifyLicense(true)) {
            //     $this->components->error('Your license is invalid. Please activate your license first.');

            //     return self::FAILURE;
            // }

            $progress->label('Downloading the latest update...');
            $progress->advance();

            $this->core->downloadUpdate($updateId, $version);

            $progress->label('Updating files and database...');
            $progress->advance();

            $this->core->updateFilesAndDatabase($version);

            $progress->label('Publishing all assets...');
            $progress->advance();
            $this->core->publishUpdateAssets();

            $progress->label('Cleaning up the system...');
            $progress->advance();
            $this->core->cleanCaches();
        } catch (Throwable $exception) {
            $this->components->error($exception->getMessage());
            $this->core->logError($exception);

            return self::FAILURE;
        }

        $progress->label('Finishing...');
        $progress->advance();
        $progress->finish();

        event(new UpdatedEvent());

        $this->components->info('Your system has been updated successfully.');

        if (confirm('Do you want run <comment>composer</comment> command?')) {
            $process = new Process(array_merge($this->composer->findComposer(), [
                select('Run <comment>composer install</comment> or <comment>composer update</comment>?', [
                    'install',
                    'update',
                ], 'install'),
            ]));
            $process->start();

            $process->wait(function ($type, $buffer): void {
                $this->components->info($buffer);
            });
        }

        return self::SUCCESS;
    }
}
