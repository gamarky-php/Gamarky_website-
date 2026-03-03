<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class I18nAuditCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'i18n:audit 
                            {--fix : Attempt to fix some issues automatically}
                            {--strict : Enable strict mode (fail on any issue)}
                            {--path= : Specific path to audit (default: all)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Audit i18n implementation for hardcoded text, missing translations, and inconsistencies';

    /**
     * Available locales
     */
    protected array $locales = ['ar', 'en', 'zh'];

    /**
     * Issues found during audit
     */
    protected array $issues = [
        'hardcoded_text' => [],
        'hardcoded_direction' => [],
        'missing_translations' => [],
        'missing_lang_files' => [],
        'inconsistent_keys' => [],
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Starting i18n Audit...');
        $this->newLine();

        // Run audit checks
        $this->auditBladeFiles();
        $this->auditLangFiles();
        $this->auditPHPFiles();

        // Display results
        $this->displayResults();

        // Return exit code
        $totalIssues = array_sum(array_map('count', $this->issues));
        
        if ($totalIssues > 0 && $this->option('strict')) {
            $this->error('❌ Audit failed in strict mode');
            return 1;
        }

        if ($totalIssues === 0) {
            $this->info('✅ No issues found! i18n implementation is clean.');
            return 0;
        }

        $this->warn("⚠️  Found {$totalIssues} potential issues");
        return 0;
    }

    /**
     * Audit Blade files for hardcoded text and direction
     */
    protected function auditBladeFiles(): void
    {
        $this->info('⏳ Auditing Blade files...');
            $path = $this->option('path') ?: resource_path('views');
            $bladeFiles = File::allFiles($path);

            foreach ($bladeFiles as $file) {
                if ($file->getExtension() !== 'php') {
                    continue;
                }

                $content = File::get($file->getPathname());
                $relativePath = str_replace(base_path(), '', $file->getPathname());

                // Check for hardcoded Arabic text (excluding comments and lang files)
                if (preg_match_all('/[\x{0600}-\x{06FF}]+/u', $content, $matches, PREG_OFFSET_CAPTURE)) {
                    foreach ($matches[0] as $match) {
                        $line = $this->getLineNumber($content, $match[1]);
                        
                        // Skip if it's in a comment or already in __() or @lang
                        $context = substr($content, max(0, $match[1] - 50), 100);
                        if (!preg_match('/(__\(|@lang|\{\{--|\*\/)/', $context)) {
                            $this->issues['hardcoded_text'][] = [
                                'file' => $relativePath,
                                'line' => $line,
                                'text' => $match[0],
                                'language' => 'Arabic',
                            ];
                        }
                    }
                }

                // Check for hardcoded Chinese text
                if (preg_match_all('/[\x{4E00}-\x{9FFF}]+/u', $content, $matches, PREG_OFFSET_CAPTURE)) {
                    foreach ($matches[0] as $match) {
                        $line = $this->getLineNumber($content, $match[1]);
                        $context = substr($content, max(0, $match[1] - 50), 100);
                        if (!preg_match('/(__\(|@lang|\{\{--|\*\/)/', $context)) {
                            $this->issues['hardcoded_text'][] = [
                                'file' => $relativePath,
                                'line' => $line,
                                'text' => $match[0],
                                'language' => 'Chinese',
                            ];
                        }
                    }
                }

                // Check for hardcoded dir="rtl" or dir="ltr"
                if (preg_match_all('/dir=["\'](?:rtl|ltr)["\']/i', $content, $matches, PREG_OFFSET_CAPTURE)) {
                    foreach ($matches[0] as $match) {
                        $line = $this->getLineNumber($content, $match[1]);
                        
                        // Skip if it's using @dir or {{ }}
                        $context = substr($content, max(0, $match[1] - 20), 50);
                        if (!preg_match('/(@dir|locale_dir\(\)|{{\s*\$currentDir)/', $context)) {
                            $this->issues['hardcoded_direction'][] = [
                                'file' => $relativePath,
                                'line' => $line,
                                'code' => $match[0],
                            ];
                        }
                    }
                }

                // Check for missing translation calls (English text without __)
                // Pattern: English words in quotes not wrapped in __()
                if (preg_match_all('/>([A-Z][a-z]+(?:\s+[A-Za-z]+){2,})</u', $content, $matches, PREG_OFFSET_CAPTURE)) {
                    foreach ($matches[1] as $match) {
                        $line = $this->getLineNumber($content, $match[1]);
                        $context = substr($content, max(0, $match[1] - 50), 100);
                        if (!preg_match('/(__\(|@lang|\{\{--|\*\/|@section|@yield)/', $context)) {
                            $this->issues['hardcoded_text'][] = [
                                'file' => $relativePath,
                                'line' => $line,
                                'text' => $match[0],
                                'language' => 'English',
                            ];
                        }
                    }
                }
            }
    }

    /**
     * Audit language files for consistency
     */
    protected function auditLangFiles(): void
    {
        $this->info('⏳ Auditing lang files consistency...');
            $langPath = lang_path();
            $langFiles = [];

            // Get all lang files for each locale
            foreach ($this->locales as $locale) {
                $localePath = $langPath . '/' . $locale;
                if (!File::exists($localePath)) {
                    $this->issues['missing_lang_files'][] = [
                        'locale' => $locale,
                        'path' => $localePath,
                    ];
                    continue;
                }

                $files = File::files($localePath);
                foreach ($files as $file) {
                    $filename = $file->getFilename();
                    $langFiles[$filename][$locale] = $file->getPathname();
                }
            }

            // Check for missing lang files across locales
            foreach ($langFiles as $filename => $localeFiles) {
                foreach ($this->locales as $locale) {
                    if (!isset($localeFiles[$locale])) {
                        $this->issues['missing_lang_files'][] = [
                            'file' => $filename,
                            'locale' => $locale,
                        ];
                    }
                }
            }

            // Check for inconsistent keys
            foreach ($langFiles as $filename => $localeFiles) {
                if (count($localeFiles) < count($this->locales)) {
                    continue;
                }

                $keys = [];
                foreach ($localeFiles as $locale => $filepath) {
                    $translations = include $filepath;
                    $keys[$locale] = $this->flattenArray($translations);
                }

                // Compare keys
                $allKeys = [];
                foreach ($keys as $locale => $localeKeys) {
                    $allKeys = array_merge($allKeys, array_keys($localeKeys));
                }
                $allKeys = array_unique($allKeys);
                
                foreach ($this->locales as $locale) {
                    $missingKeys = array_diff($allKeys, array_keys($keys[$locale]));
                    foreach ($missingKeys as $key) {
                        $this->issues['inconsistent_keys'][] = [
                            'file' => $filename,
                            'locale' => $locale,
                            'key' => $key,
                        ];
                    }
                }
            }
    }

    /**
     * Audit PHP files for hardcoded text
     */
    protected function auditPHPFiles(): void
    {
        $this->info('⏳ Auditing PHP files (Controllers, Models)...');
            $paths = [
                app_path('Http/Controllers'),
                app_path('Models'),
                app_path('Services'),
            ];

            foreach ($paths as $path) {
                if (!File::exists($path)) {
                    continue;
                }

                $phpFiles = File::allFiles($path);

                foreach ($phpFiles as $file) {
                    $content = File::get($file->getPathname());
                    $relativePath = str_replace(base_path(), '', $file->getPathname());

                    // Check for hardcoded Arabic text
                    if (preg_match_all('/[\x{0600}-\x{06FF}]+/u', $content, $matches, PREG_OFFSET_CAPTURE)) {
                        foreach ($matches[0] as $match) {
                            $line = $this->getLineNumber($content, $match[1]);
                            $context = substr($content, max(0, $match[1] - 50), 100);
                            if (!preg_match('/(__\(|trans\(|\*\/|\/\/)/', $context)) {
                                $this->issues['hardcoded_text'][] = [
                                    'file' => $relativePath,
                                    'line' => $line,
                                    'text' => $match[0],
                                    'language' => 'Arabic',
                                ];
                            }
                        }
                    }

                    // Check for hardcoded Chinese text
                    if (preg_match_all('/[\x{4E00}-\x{9FFF}]+/u', $content, $matches, PREG_OFFSET_CAPTURE)) {
                        foreach ($matches[0] as $match) {
                            $line = $this->getLineNumber($content, $match[1]);
                            $context = substr($content, max(0, $match[1] - 50), 100);
                            if (!preg_match('/(__\(|trans\(|\*\/|\/\/)/', $context)) {
                                $this->issues['hardcoded_text'][] = [
                                    'file' => $relativePath,
                                    'line' => $line,
                                    'text' => $match[0],
                                    'language' => 'Chinese',
                                ];
                            }
                        }
                    }
                }
            }
    }

    /**
     * Display audit results
     */
    protected function displayResults(): void
    {
        $this->newLine();
        $this->info('📊 Audit Results:');
        $this->newLine();

        // Hardcoded Text
        if (count($this->issues['hardcoded_text']) > 0) {
            $this->warn('⚠️  Hardcoded Text Found: ' . count($this->issues['hardcoded_text']));
            $this->table(
                ['File', 'Line', 'Language', 'Text'],
                collect($this->issues['hardcoded_text'])->map(fn($issue) => [
                    Str::limit($issue['file'], 50),
                    $issue['line'],
                    $issue['language'],
                    Str::limit($issue['text'], 40),
                ])->take(20)->toArray()
            );
            if (count($this->issues['hardcoded_text']) > 20) {
                $this->line('... and ' . (count($this->issues['hardcoded_text']) - 20) . ' more');
            }
            $this->newLine();
        }

        // Hardcoded Direction
        if (count($this->issues['hardcoded_direction']) > 0) {
            $this->warn('⚠️  Hardcoded Direction Attributes: ' . count($this->issues['hardcoded_direction']));
            $this->table(
                ['File', 'Line', 'Code'],
                collect($this->issues['hardcoded_direction'])->map(fn($issue) => [
                    Str::limit($issue['file'], 50),
                    $issue['line'],
                    $issue['code'],
                ])->take(10)->toArray()
            );
            if (count($this->issues['hardcoded_direction']) > 10) {
                $this->line('... and ' . (count($this->issues['hardcoded_direction']) - 10) . ' more');
            }
            $this->newLine();
        }

        // Missing Lang Files
        if (count($this->issues['missing_lang_files']) > 0) {
            $this->warn('⚠️  Missing Lang Files: ' . count($this->issues['missing_lang_files']));
            $this->table(
                ['File', 'Locale'],
                collect($this->issues['missing_lang_files'])->map(fn($issue) => [
                    $issue['file'] ?? $issue['path'] ?? 'N/A',
                    $issue['locale'],
                ])->toArray()
            );
            $this->newLine();
        }

        // Inconsistent Keys
        if (count($this->issues['inconsistent_keys']) > 0) {
            $this->warn('⚠️  Inconsistent Translation Keys: ' . count($this->issues['inconsistent_keys']));
            $this->table(
                ['File', 'Locale', 'Missing Key'],
                collect($this->issues['inconsistent_keys'])->map(fn($issue) => [
                    $issue['file'],
                    $issue['locale'],
                    Str::limit($issue['key'], 40),
                ])->take(15)->toArray()
            );
            if (count($this->issues['inconsistent_keys']) > 15) {
                $this->line('... and ' . (count($this->issues['inconsistent_keys']) - 15) . ' more');
            }
            $this->newLine();
        }

        // Summary
        $totalIssues = array_sum(array_map('count', $this->issues));
        if ($totalIssues === 0) {
            $this->info('✅ No issues found!');
        } else {
            $this->warn("Total Issues: {$totalIssues}");
            $this->newLine();
            $this->info('💡 Recommendations:');
            $this->line('  • Wrap hardcoded text in __() or @lang()');
            $this->line('  • Replace dir="rtl" with dir="@dir" or dir="{{ $currentDir }}"');
            $this->line('  • Create missing translation files');
            $this->line('  • Sync translation keys across all locales');
            $this->newLine();
            $this->comment('Run with --fix flag to attempt automatic fixes (coming soon)');
        }
    }

    /**
     * Get line number from offset
     */
    protected function getLineNumber(string $content, int $offset): int
    {
        return substr_count(substr($content, 0, $offset), "\n") + 1;
    }

    /**
     * Flatten nested array with dot notation
     */
    protected function flattenArray(array $array, string $prefix = ''): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            $newKey = $prefix ? "{$prefix}.{$key}" : $key;
            if (is_array($value)) {
                $result = array_merge($result, $this->flattenArray($value, $newKey));
            } else {
                $result[$newKey] = $value;
            }
        }
        return $result;
    }
}
