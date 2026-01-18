<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FeatureFlag;

class FeatureFlagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $features = [
            [
                'key' => 'advanced_analytics',
                'name' => 'Advanced Analytics Dashboard',
                'description' => 'Access to advanced analytics and reporting features',
                'is_active' => false,
                'rules' => ['roles' => ['admin']],
            ],
            [
                'key' => 'ai_cost_predictor',
                'name' => 'AI Cost Predictor',
                'description' => 'AI-powered cost prediction and optimization',
                'is_active' => false,
                'rules' => ['percentage' => 10], // 10% rollout
            ],
            [
                'key' => 'real_time_tracking',
                'name' => 'Real-time Shipment Tracking',
                'description' => 'Live GPS tracking of shipments',
                'is_active' => true,
            ],
            [
                'key' => 'customs_automation',
                'name' => 'Automated Customs Clearance',
                'description' => 'AI-assisted customs documentation',
                'is_active' => false,
                'rules' => ['roles' => ['admin', 'manager']],
            ],
            [
                'key' => 'multi_currency',
                'name' => 'Multi-Currency Support',
                'description' => 'Support for multiple currencies in quotes',
                'is_active' => true,
            ],
            [
                'key' => 'api_webhooks',
                'name' => 'API Webhooks',
                'description' => 'Webhook notifications for events',
                'is_active' => true,
            ],
            [
                'key' => 'export_module',
                'name' => 'Export Module',
                'description' => 'Export shipping and cost calculation features',
                'is_active' => true,
            ],
            [
                'key' => 'manufacturing_module',
                'name' => 'Manufacturing Module',
                'description' => 'Manufacturing cost calculation features',
                'is_active' => false,
                'rules' => ['percentage' => 25], // 25% rollout
            ],
            [
                'key' => 'mobile_app',
                'name' => 'Mobile App Features',
                'description' => 'Enhanced features for mobile app users',
                'is_active' => false,
            ],
            [
                'key' => 'document_ocr',
                'name' => 'Document OCR',
                'description' => 'Automatic document scanning and data extraction',
                'is_active' => false,
                'rules' => ['roles' => ['admin']],
            ],
        ];

        foreach ($features as $feature) {
            FeatureFlag::updateOrCreate(
                ['key' => $feature['key']],
                $feature
            );
        }

        $this->command->info('✓ Created ' . count($features) . ' feature flags');
    }
}
