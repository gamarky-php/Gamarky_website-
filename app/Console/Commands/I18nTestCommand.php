<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class I18nTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'i18n:test 
                            {--locale= : Test specific locale only}
                            {--file= : Test specific file only}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test i18n translation completeness and consistency';

    protected array $locales = ['ar', 'en', 'zh'];
    protected array $results = [
        'passed' => 0,
        'failed' => 0,
        'warnings' => 0,
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 Testing i18n Implementation...');
        $this->newLine();

        // Test 1: All locales have same files
        $this->testFileParity();

        // Test 2: All translation keys are consistent
        $this->testKeyConsistency();

        // Test 3: No empty translations
        $this->testEmptyTranslations();

        // Test 4: Translation parameter consistency
        $this->testParameterConsistency();

        // Test 5: Helper functions work
        $this->testHelperFunctions();

        // Display summary
        $this->displaySummary();

        return $this->results['failed'] === 0 ? 0 : 1;
    }

    /**
     * Test that all locales have the same lang files
     */
    protected function testFileParity(): void
    {
        $this->info('⏳ Testing file parity across locales...');
            $langPath = lang_path();
            $filesByLocale = [];

            foreach ($this->locales as $locale) {
                $localePath = $langPath . '/' . $locale;
                if (!File::exists($localePath)) {
                    $this->results['failed']++;
                    $this->error("Missing locale directory: {$locale}");
                    return;
                }

                $files = File::files($localePath);
                $filesByLocale[$locale] = collect($files)->map(fn($file) => $file->getFilename())->sort()->values()->toArray();
            }

            // Check if all locales have same files
            $baseFiles = $filesByLocale[$this->locales[0]];
            $allMatch = true;

            foreach ($this->locales as $locale) {
                $diff = array_diff($baseFiles, $filesByLocale[$locale]);
                if (!empty($diff)) {
                    $allMatch = false;
                    $this->results['failed']++;
                    $this->error("  Missing files in {$locale}: " . implode(', ', $diff));
                }

                $extra = array_diff($filesByLocale[$locale], $baseFiles);
                if (!empty($extra)) {
                    $allMatch = false;
                    $this->results['warnings']++;
                    $this->warn("  Extra files in {$locale}: " . implode(', ', $extra));
                }
            }

            if ($allMatch) {
                $this->results['passed']++;
            }
    }

    /**
     * Test translation key consistency
     */
    protected function testKeyConsistency(): void
    {
        $this->info('⏳ Testing translation key consistency...');
            $langPath = lang_path();
            $langFiles = [];

            // Load all translation files
            foreach ($this->locales as $locale) {
                $localePath = $langPath . '/' . $locale;
                $files = File::files($localePath);

                foreach ($files as $file) {
                    $filename = $file->getFilename();
                    $langFiles[$filename][$locale] = include $file->getPathname();
                }
            }

            $hasInconsistency = false;

            foreach ($langFiles as $filename => $localeData) {
                if ($this->option('file') && $filename !== $this->option('file')) {
                    continue;
                }

                // Get all keys from all locales for this file
                $keysByLocale = [];
                foreach ($this->locales as $locale) {
                    if (isset($localeData[$locale]) && is_array($localeData[$locale])) {
                        $keysByLocale[$locale] = $this->flattenArray($localeData[$locale]);
                    }
                }

                if (empty($keysByLocale)) {
                    continue;
                }

                // Find missing keys
                $allKeys = [];
                foreach ($keysByLocale as $locale => $keys) {
                    $allKeys = array_merge($allKeys, array_keys($keys));
                }
                $allKeys = array_unique($allKeys);
                
                foreach ($this->locales as $locale) {
                    if (!isset($keysByLocale[$locale])) {
                        continue;
                    }

                    $missingKeys = array_diff($allKeys, array_keys($keysByLocale[$locale]));
                    
                    if (!empty($missingKeys)) {
                        $hasInconsistency = true;
                        $this->results['failed']++;
                        $this->error("Missing keys in {$filename} ({$locale}): " . implode(', ', array_slice($missingKeys, 0, 5)));
                        
                        if (count($missingKeys) > 5) {
                            $this->line("  ... and " . (count($missingKeys) - 5) . " more");
                        }
                    }
                }
            }

            if (!$hasInconsistency) {
                $this->results['passed']++;
            }
    }

    /**
     * Test for empty translations
     */
    protected function testEmptyTranslations(): void
    {
        $this->info('⏳ Testing for empty translations...');
            $langPath = lang_path();
            $hasEmpty = false;

            foreach ($this->locales as $locale) {
                if ($this->option('locale') && $locale !== $this->option('locale')) {
                    continue;
                }

                $localePath = $langPath . '/' . $locale;
                $files = File::files($localePath);

                foreach ($files as $file) {
                    if ($this->option('file') && $file->getFilename() !== $this->option('file')) {
                        continue;
                    }

                    $translations = include $file->getPathname();
                    $flatTranslations = $this->flattenArray($translations);

                    foreach ($flatTranslations as $key => $value) {
                        if (empty($value) || trim($value) === '') {
                            $hasEmpty = true;
                            $this->results['warnings']++;
                            $this->warn("Empty translation in {$file->getFilename()} ({$locale}): {$key}");
                        }
                    }
                }
            }

            if (!$hasEmpty) {
                $this->results['passed']++;
            }
    }

    /**
     * Test parameter consistency across locales
     */
    protected function testParameterConsistency(): void
    {
        $this->info('⏳ Testing translation parameter consistency...');
            $langPath = lang_path();
            $langFiles = [];

            // Load all translation files
            foreach ($this->locales as $locale) {
                $localePath = $langPath . '/' . $locale;
                $files = File::files($localePath);

                foreach ($files as $file) {
                    $filename = $file->getFilename();
                    $langFiles[$filename][$locale] = include $file->getPathname();
                }
            }

            $hasInconsistency = false;

            foreach ($langFiles as $filename => $localeData) {
                if ($this->option('file') && $filename !== $this->option('file')) {
                    continue;
                }

                $keysByLocale = [];
                foreach ($this->locales as $locale) {
                    if (isset($localeData[$locale]) && is_array($localeData[$locale])) {
                        $keysByLocale[$locale] = $this->flattenArray($localeData[$locale]);
                    }
                }

                if (empty($keysByLocale)) {
                    continue;
                }

                // Get all common keys
                $firstLocaleKeys = reset($keysByLocale);
                $allKeys = [];
                foreach (array_keys($firstLocaleKeys) as $key) {
                    $existsInAll = true;
                    foreach ($this->locales as $locale) {
                        if (!isset($keysByLocale[$locale][$key])) {
                            $existsInAll = false;
                            break;
                        }
                    }
                    if ($existsInAll) {
                        $allKeys[$key] = true;
                    }
                }

                foreach ($allKeys as $key => $value) {
                    // Extract parameters from all locales
                    $paramsByLocale = [];
                    foreach ($this->locales as $locale) {
                        if (isset($keysByLocale[$locale][$key])) {
                            preg_match_all('/:([a-zA-Z_]+)/', $keysByLocale[$locale][$key], $matches);
                            $paramsByLocale[$locale] = $matches[1] ?? [];
                        }
                    }

                    // Check if all locales have same parameters
                    $baseParams = sort($paramsByLocale[$this->locales[0]]);
                    foreach ($this->locales as $locale) {
                        $localeParams = sort($paramsByLocale[$locale]);
                        if ($baseParams !== $localeParams) {
                            $hasInconsistency = true;
                            $this->results['warnings']++;
                            $this->warn("Parameter mismatch in {$filename} - key: {$key}");
                            break;
                        }
                    }
                }
            }

            if (!$hasInconsistency) {
                $this->results['passed']++;
            }
    }

    /**
     * Test that helper functions work correctly
     */
    protected function testHelperFunctions(): void
    {
        $this->info('⏳ Testing i18n helper functions...');
            $testsPassed = true;

            // Test locale_dir()
            try {
                $dir = locale_dir('ar');
                if ($dir !== 'rtl') {
                    $testsPassed = false;
                    $this->error('locale_dir(\'ar\') should return \'rtl\'');
                }

                $dir = locale_dir('en');
                if ($dir !== 'ltr') {
                    $testsPassed = false;
                    $this->error('locale_dir(\'en\') should return \'ltr\'');
                }
            } catch (\Exception $e) {
                $testsPassed = false;
                $this->error('locale_dir() failed: ' . $e->getMessage());
            }

            // Test is_rtl()
            try {
                app()->setLocale('ar');
                if (!is_rtl()) {
                    $testsPassed = false;
                    $this->error('is_rtl() should return true for Arabic');
                }

                app()->setLocale('en');
                if (is_rtl()) {
                    $testsPassed = false;
                    $this->error('is_rtl() should return false for English');
                }
            } catch (\Exception $e) {
                $testsPassed = false;
                $this->error('is_rtl() failed: ' . $e->getMessage());
            }

            // Test locale_font()
            try {
                $font = locale_font('ar');
                if (empty($font)) {
                    $testsPassed = false;
                    $this->error('locale_font(\'ar\') returned empty');
                }
            } catch (\Exception $e) {
                $testsPassed = false;
                $this->error('locale_font() failed: ' . $e->getMessage());
            }

            // Test available_locales()
            try {
                $locales = available_locales();
                if (!is_array($locales) || count($locales) !== 3) {
                    $testsPassed = false;
                    $this->error('available_locales() should return array of 3 locales');
                }
            } catch (\Exception $e) {
                $testsPassed = false;
                $this->error('available_locales() failed: ' . $e->getMessage());
            }

            if ($testsPassed) {
                $this->results['passed']++;
            } else {
                $this->results['failed']++;
            }
    }

    /**
     * Display test summary
     */
    protected function displaySummary(): void
    {
        $this->newLine();
        $this->info('📊 Test Summary:');
        $this->newLine();

        $this->line("✅ Passed:   {$this->results['passed']}");
        $this->line("❌ Failed:   {$this->results['failed']}");
        $this->line("⚠️  Warnings: {$this->results['warnings']}");
        $this->newLine();

        if ($this->results['failed'] === 0 && $this->results['warnings'] === 0) {
            $this->info('🎉 All i18n tests passed!');
        } elseif ($this->results['failed'] === 0) {
            $this->warn('⚠️  Tests passed with warnings');
        } else {
            $this->error('❌ Some tests failed');
        }
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
