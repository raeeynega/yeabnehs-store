<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ObfuscateTranslations extends Command
{
    protected $signature = 'translations:obfuscate';
    protected $description = 'Two-step obfuscate translation JSON from lang/source/ into lang/{locale}/messages.php';

    private string $obfuscationKey = 'YeaBn3h$_3cur3_K3y_2026!#x7mQ';

    public function handle(): int
    {
        $locales = ['en', 'am', 'om'];

        foreach ($locales as $locale) {
            $jsonPath = lang_path("source/{$locale}.json");
            $phpPath = lang_path("{$locale}/messages.php");

            if (!file_exists($jsonPath)) {
                $this->warn("Skipping lang/source/{$locale}.json — not found");
                continue;
            }

            $json = file_get_contents($jsonPath);
            $step1 = base64_encode(gzencode($json, 9));
            $step2 = base64_encode($this->xorEncrypt($step1, $this->obfuscationKey));

            $php = "<?php\n"
                . "// Obfuscated {$locale} translations — two-step obfuscation\n"
                . "// Edit lang/source/{$locale}.json then re-run: php artisan translations:obfuscate\n"
                . "\$_x=function(\$d,\$k){\$r='';for(\$i=0;\$i<strlen(\$d);\$i++){\$r.=\$d[\$i]^\$k[\$i%strlen(\$k)];}return \$r;};\n"
                . "return json_decode(gzdecode(base64_decode(\$_x(base64_decode('" . $step2 . "'), '" . $this->obfuscationKey . "'))), true);\n";

            file_put_contents($phpPath, $php);
            $this->info("Obfuscated {$locale}.json → {$locale}/messages.php (" . strlen($step2) . " chars)");
        }

        $this->info('Done. Source JSON in lang/source/, obfuscated PHP in lang/{locale}/messages.php');
        return self::SUCCESS;
    }

    private function xorEncrypt(string $data, string $key): string
    {
        $keyLen = strlen($key);
        $result = '';
        for ($i = 0; $i < strlen($data); $i++) {
            $result .= $data[$i] ^ $key[$i % $keyLen];
        }
        return $result;
    }
}
