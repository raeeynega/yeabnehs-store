<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class ImmutableAuditLogger
{
    /**
     * Append an entry to the immutable audit chain.
     * Every entry is SHA-256 hashed with the previous entry's hash,
     * forming a tamper-evident blockchain-style chain.
     */
    public static function log(
        string $action,
        ?string $entityType = null,
        ?int $entityId = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?string $riskLevel = 'low',
        ?Request $request = null,
    ): AuditLog {
        $lastEntry = AuditLog::orderByDesc('chain_index')->first();

        $entry = new AuditLog();
        $entry->chain_index   = $lastEntry ? $lastEntry->chain_index + 1 : 0;
        $entry->timestamp     = now();
        $entry->user_id       = auth()->id();
        $entry->action        = $action;
        $entry->entity_type   = $entityType;
        $entry->entity_id     = $entityId;
        $entry->old_values    = $oldValues;
        $entry->new_values    = $newValues;
        $entry->ip_address    = $request?->ip() ?? request()->ip();
        $entry->user_agent    = $request?->userAgent() ?? request()->userAgent();
        $entry->request_url   = $request?->url() ?? request()->url();
        $entry->request_method = $request?->method() ?? request()->method();
        $entry->risk_level    = $riskLevel;
        $entry->previous_hash = $lastEntry?->chain_hash ?? str_repeat('0', 64);
        $entry->chain_hash    = $entry->computeHash();
        $entry->save();

        return $entry;
    }

    /**
     * Verify the entire chain integrity from a given index.
     * Returns [valid: bool, brokenAt: ?int, errors: array].
     */
    public static function verifyChain(int $startIndex = 0): array
    {
        $entries = AuditLog::where('chain_index', '>=', $startIndex)
            ->orderBy('chain_index', 'asc')
            ->get();

        $errors = [];
        $prevHash = $startIndex > 0
            ? (AuditLog::where('chain_index', $startIndex - 1)->first()?->chain_hash ?? str_repeat('0', 64))
            : str_repeat('0', 64);

        foreach ($entries as $entry) {
            // Check previous_hash link
            if ($entry->previous_hash !== $prevHash) {
                $errors[] = "Chain break at index {$entry->chain_index}: previous_hash mismatch";
                return ['valid' => false, 'brokenAt' => $entry->chain_index, 'errors' => $errors];
            }

            // Recompute and verify hash
            $expectedHash = $entry->computeHash();
            if ($entry->chain_hash !== $expectedHash) {
                $errors[] = "Tampering detected at index {$entry->chain_index}: hash mismatch";
                return ['valid' => false, 'brokenAt' => $entry->chain_index, 'errors' => $errors];
            }

            $prevHash = $entry->chain_hash;
        }

        return ['valid' => true, 'brokenAt' => null, 'errors' => []];
    }

    /**
     * Get chain statistics.
     */
    public static function chainStats(): array
    {
        $total = AuditLog::count();
        $latest = AuditLog::orderByDesc('chain_index')->first();

        return [
            'total_entries'  => $total,
            'latest_index'   => $latest?->chain_index ?? -1,
            'latest_hash'    => $latest?->chain_hash ?? 'N/A',
            'latest_time'    => $latest?->timestamp?->toIso8601String() ?? 'N/A',
            'chain_integrity' => $total > 0 ? self::verifyChain()->valid : true,
        ];
    }
}
