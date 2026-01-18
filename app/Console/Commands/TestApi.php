<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Api\V1\{HomeController, ImportController};
use Illuminate\Http\Request;

class TestApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-api';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test API endpoints';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== Testing API Endpoints ===');

        // Test HomeController
        $homeController = app(HomeController::class);
        
        $this->info("\n1. HomeController index():");
        $response = $homeController->index();
        $this->line(json_encode($response->getData(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        $this->info("\n2. HomeController menus():");
        $response = $homeController->menus();
        $this->line(json_encode($response->getData(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        // Test ImportController
        $importController = app(ImportController::class);
        
        $this->info("\n3. ImportController prefill():");
        $response = $importController->prefill();
        $this->line(json_encode($response->getData(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        $this->info("\n4. ImportController compute() with test data:");
        $request = new Request([
            'origin_country' => 'CN',
            'destination_port' => 1,
            'shipping_type' => 1,
            'product_value' => 1000,
            'product_currency' => 'USD',
            'weight' => 25.5,
            'product_category' => 'electronics',
            'add_insurance' => true
        ]);
        
        try {
            $response = $importController->compute($request);
            $this->line(json_encode($response->getData(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }

        $this->info("\n=== API Test Complete ===");
    }
}
