<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class NginxStatus extends Command
{
    protected $signature = 'nginx:status';
    protected $description = 'Show Nginx installation status, config test, and connection info';

    public function handle(): int
    {
        $this->info('=== NGINX STATUS ===');
        $this->newLine();

        // 1. Nginx binary
        $this->info('--- Binary ---');
        $version = shell_exec('nginx -v 2>&1') ?? 'NOT FOUND';
        $this->line("  Version: {$version}");

        $path = shell_exec('which nginx 2>/dev/null') ?? 'NOT FOUND';
        $this->line("  Path:    {$path}");
        $this->newLine();

        // 2. Process status
        $this->info('--- Processes ---');
        $ps = shell_exec('ps aux | grep "[n]ginx" 2>/dev/null') ?? '';
        if (empty(trim($ps))) {
            $this->warn("  Nginx is NOT running!");
        } else {
            $lines = array_filter(explode("\n", trim($ps)));
            $this->line("  " . count($lines) . " process(es) running:");
            foreach ($lines as $line) {
                $this->line("  " . preg_replace('/\s+/', ' ', trim($line)));
            }
        }
        $this->newLine();

        // 3. Config test
        $this->info('--- Config Test ---');
        $test = shell_exec('nginx -t 2>&1') ?? 'UNKNOWN';
        foreach (explode("\n", trim($test)) as $line) {
            $this->line("  {$line}");
        }
        $this->newLine();

        // 4. Active config
        $this->info('--- Active Nginx Config ---');
        $config = shell_exec('cat /etc/nginx/conf.d/default.conf 2>/dev/null') ?? 'NOT FOUND';
        if ($config !== 'NOT FOUND') {
            // Show first 30 lines
            $configLines = explode("\n", $config);
            $total = count($configLines);
            $show = array_slice($configLines, 0, 30);
            foreach ($show as $i => $line) {
                $this->line("  " . str_pad($i + 1, 3) . " | {$line}");
            }
            if ($total > 30) {
                $this->line("  ... ({$total} total lines)");
            }
        }
        $this->newLine();

        // 5. Listen ports
        $this->info('--- Listening Ports ---');
        $ports = shell_exec('ss -tlnp 2>/dev/null || netstat -tlnp 2>/dev/null') ?? 'UNKNOWN';
        foreach (explode("\n", trim($ports)) as $line) {
            if (str_contains($line, 'LISTEN') || str_contains($line, 'State')) {
                $this->line("  " . preg_replace('/\s+/', ' ', trim($line)));
            }
        }
        $this->newLine();

        // 6. PHP-FPM status
        $this->info('--- PHP-FPM ---');
        $fpm = shell_exec('ps aux | grep "[p]hp-fpm" 2>/dev/null') ?? '';
        if (empty(trim($fpm))) {
            $this->warn("  PHP-FPM is NOT running!");
        } else {
            $fpmLines = array_filter(explode("\n", trim($fpm)));
            $this->line("  " . count($fpmLines) . " process(es) running");
        }
        $fpmSock = file_exists('/run/php-fpm.sock');
        $this->line("  Socket: " . ($fpmSock ? '/run/php-fpm.sock EXISTS' : 'MISSING'));
        $this->newLine();

        // 7. PORT env
        $this->info('--- Environment ---');
        $port = env('PORT', 'NOT SET');
        $this->line("  PORT = {$port}");
        $this->line("  APP_ENV = " . env('APP_ENV', 'NOT SET'));
        $this->line("  APP_DEBUG = " . env('APP_DEBUG', 'NOT SET'));
        $this->newLine();

        // 8. Log files
        $this->info('--- Log Files ---');
        $logs = [
            '/var/log/nginx/error.log' => 'Nginx Error Log',
            '/var/log/nginx/access.log' => 'Nginx Access Log',
            '/var/log/supervisor/supervisord.log' => 'Supervisor Log',
            '/var/log/supervisor/nginx.err.log' => 'Nginx Supervisor Log',
            '/var/log/supervisor/php-fpm.err.log' => 'PHP-FPM Supervisor Log',
        ];
        foreach ($logs as $file => $label) {
            if (file_exists($file)) {
                $size = number_format(filesize($file));
                $this->line("  {$label}: {$file} ({$size} bytes)");
            } else {
                $this->line("  {$label}: {$file} (not found)");
            }
        }

        return self::SUCCESS;
    }
}
