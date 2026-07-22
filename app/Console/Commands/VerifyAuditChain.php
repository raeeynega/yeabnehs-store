<?php

namespace App\Console\Commands;

use App\Models\AuditLog;
use App\Services\ImmutableAuditLogger;
use Illuminate\Console\Command;

class VerifyAuditChain extends Command
{
    protected $signature = 'audit:verify {--start=0 : Starting chain index} {--stats : Show chain statistics only}';
    protected $description = 'Verify the immutable audit log hash chain integrity (ISO 27001 A.12.4.1)';

    public function handle(): int
    {
        if ($this->option('stats')) {
            $stats = ImmutableAuditLogger::chainStats();
            $this->table(['Metric', 'Value'], [
                ['Total Entries', number_format($stats['total_entries'])],
                ['Latest Index', $stats['latest_index']],
                ['Latest Hash', substr($stats['latest_hash'], 0, 32) . '...'],
                ['Latest Timestamp', $stats['latest_time']],
                ['Chain Integrity', $stats['chain_integrity'] ? 'VALID' : 'BROKEN'],
            ]);
            return self::SUCCESS;
        }

        $start = (int) $this->option('start');
        $total = AuditLog::count();

        if ($total === 0) {
            $this->warn('No audit log entries found.');
            return self::SUCCESS;
        }

        $this->info("Verifying audit chain from index {$start} (" . number_format($total) . " total entries)...");
        $this->newLine();

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        // Verify in chunks for memory efficiency
        $chunkSize = 1000;
        $lastHash = $start > 0
            ? AuditLog::where('chain_index', $start - 1)->value('chain_hash')
            : str_repeat('0', 64);

        $broken = false;
        $checked = 0;

        AuditLog::where('chain_index', '>=', $start)
            ->orderBy('chain_index')
            ->chunk($chunkSize, function ($entries) use (&$lastHash, &$broken, &$checked, $bar, $start) {
                foreach ($entries as $entry) {
                    $bar->advance();

                    if ($entry->previous_hash !== $lastHash) {
                        $bar->finish();
                        $this->newLine();
                        $this->error("CHAIN BREAK at index {$entry->chain_index}");
                        $this->error("  Expected previous_hash: " . substr($lastHash, 0, 16) . "...");
                        $this->error("  Actual previous_hash:   " . substr($entry->previous_hash, 0, 16) . "...");
                        $broken = true;
                        return false;
                    }

                    $expectedHash = $entry->computeHash();
                    if ($entry->chain_hash !== $expectedHash) {
                        $bar->finish();
                        $this->newLine();
                        $this->error("TAMPERING at index {$entry->chain_index}");
                        $this->error("  Action: {$entry->action}");
                        $this->error("  Expected hash: " . substr($expectedHash, 0, 16) . "...");
                        $this->error("  Actual hash:   " . substr($entry->chain_hash, 0, 16) . "...");
                        $broken = true;
                        return false;
                    }

                    $lastHash = $entry->chain_hash;
                    $checked++;
                }
            });

        $bar->finish();
        $this->newLine();
        $this->newLine();

        if ($broken) {
            $this->error("CHAIN INTEGRITY: FAILED");
            return self::FAILURE;
        }

        $this->info("CHAIN INTEGRITY: VALID ({$checked} entries verified)");
        return self::SUCCESS;
    }
}
