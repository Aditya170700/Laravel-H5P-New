<?php

namespace Djoudi\LaravelH5p\Console\Commands;

use Djoudi\LaravelH5p\Eloquents\H5pContent;
use Djoudi\LaravelH5p\Eloquents\H5pLibrary;
use Djoudi\LaravelH5p\Eloquents\H5pResult;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class StatusCommand extends Command
{
    protected $signature = 'h5p:status';

    protected $description = 'Show H5P installation status and statistics';

    public function handle(): int
    {
        $this->info(' ');
        $this->info('╔══════════════════════════════════════════╗');
        $this->info('║          H5P Installation Status         ║');
        $this->info('╚══════════════════════════════════════════╝');
        $this->info(' ');

        // Check tables
        $tables = [
            'h5p_contents' => Schema::hasTable('h5p_contents'),
            'h5p_libraries' => Schema::hasTable('h5p_libraries'),
            'h5p_results' => Schema::hasTable('h5p_results'),
        ];

        $this->info('📋 Database Tables:');
        foreach ($tables as $table => $exists) {
            $status = $exists ? '<info>✓</info>' : '<error>✗</error>';
            $this->line("   {$status} {$table}");
        }
        $this->newLine();

        // Statistics
        if (Schema::hasTable('h5p_contents')) {
            $this->info('📊 Statistics:');
            $this->table([], [
                ['Libraries', H5pLibrary::count()],
                ['Contents', H5pContent::count()],
                ['Results', H5pResult::count()],
            ]);
        }

        // Check directories
        $directories = [
            'Storage' => storage_path('app/public/h5p'),
            'Libraries' => storage_path('app/public/h5p/libraries'),
            'Content' => storage_path('app/public/h5p/content'),
        ];

        $this->info('📁 Directories:');
        foreach ($directories as $name => $path) {
            $exists = is_dir($path);
            $status = $exists ? '<info>✓</info>' : '<error>✗</error>';
            $this->line("   {$status} {$name}: {$path}");
        }
        $this->newLine();

        // LRS Status
        $this->info('🔗 LRS Integration:');
        $lrsEnabled = config('lrs.enabled', false);
        $lrsStatus = $lrsEnabled ? '<info>Enabled</info>' : '<comment>Disabled</comment>';
        $this->line("   Status: {$lrsStatus}");
        if ($lrsEnabled) {
            $this->line("   Endpoint: " . config('lrs.endpoint', 'Not configured'));
        }
        $this->newLine();

        return self::SUCCESS;
    }
}
