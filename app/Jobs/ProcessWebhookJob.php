<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\AuditLog;

class ProcessWebhookJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    public $tries = 5;
    public $timeout = 120;
    public $backoff = [30, 60, 120, 300, 600]; // Progressive backoff

    protected $webhookUrl;
    protected $payload;
    protected $headers;
    protected $eventType;

    /**
     * Create a new job instance.
     */
    public function __construct(string $webhookUrl, array $payload, string $eventType = 'generic', array $headers = [])
    {
        $this->webhookUrl = $webhookUrl;
        $this->payload = $payload;
        $this->eventType = $eventType;
        $this->headers = array_merge([
            'Content-Type' => 'application/json',
            'User-Agent' => 'Gamarky-Webhook/1.0',
        ], $headers);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::channel('webhooks')->info("Processing webhook", [
            'url' => $this->webhookUrl,
            'event' => $this->eventType,
            'attempt' => $this->attempts(),
        ]);

        try {
            $response = Http::withHeaders($this->headers)
                ->timeout($this->timeout)
                ->retry(3, 1000) // Retry 3 times with 1s delay
                ->post($this->webhookUrl, $this->payload);

            if ($response->successful()) {
                Log::channel('webhooks')->info("Webhook delivered successfully", [
                    'url' => $this->webhookUrl,
                    'event' => $this->eventType,
                    'status' => $response->status(),
                    'response_time' => $response->transferStats->getTransferTime(),
                ]);

                // Log successful webhook delivery
                $this->logWebhookDelivery('success', $response->status());

            } else {
                Log::channel('webhooks')->warning("Webhook returned non-2xx status", [
                    'url' => $this->webhookUrl,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                throw new \Exception("Webhook failed with status {$response->status()}");
            }

        } catch (\Exception $e) {
            Log::channel('webhooks')->error("Webhook delivery failed", [
                'url' => $this->webhookUrl,
                'event' => $this->eventType,
                'attempt' => $this->attempts(),
                'error' => $e->getMessage(),
            ]);

            $this->logWebhookDelivery('failed', null, $e->getMessage());

            throw $e; // Re-throw to trigger retry
        }
    }

    /**
     * Handle job failure
     */
    public function failed(\Throwable $exception): void
    {
        Log::channel('webhooks')->critical("Webhook failed permanently after {$this->tries} attempts", [
            'url' => $this->webhookUrl,
            'event' => $this->eventType,
            'error' => $exception->getMessage(),
        ]);

        $this->logWebhookDelivery('permanent_failure', null, $exception->getMessage());

        // Optionally: Disable webhook endpoint or notify admin
    }

    /**
     * Log webhook delivery attempt
     */
    protected function logWebhookDelivery(string $status, ?int $httpStatus = null, ?string $error = null): void
    {
        // Store webhook delivery log in database
        \DB::table('webhook_logs')->insert([
            'url' => $this->webhookUrl,
            'event_type' => $this->eventType,
            'payload' => json_encode($this->payload),
            'status' => $status,
            'http_status' => $httpStatus,
            'error_message' => $error,
            'attempt' => $this->attempts(),
            'created_at' => now(),
        ]);
    }

    /**
     * Get the tags that should be assigned to the job.
     */
    public function tags(): array
    {
        return ['webhook', $this->eventType, parse_url($this->webhookUrl, PHP_URL_HOST)];
    }
}
