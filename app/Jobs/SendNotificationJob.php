<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class SendNotificationJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    public $tries = 3;
    public $timeout = 60;
    public $backoff = [10, 30, 60]; // Retry after 10s, 30s, 60s

    protected $user;
    protected $notificationType;
    protected $data;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user, string $notificationType, array $data = [])
    {
        $this->user = $user;
        $this->notificationType = $notificationType;
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::channel('notifications')->info("Sending {$this->notificationType} to user {$this->user->id}");

        try {
            switch ($this->notificationType) {
                case 'shipment_status_update':
                    $this->sendShipmentStatusUpdate();
                    break;

                case 'booking_confirmation':
                    $this->sendBookingConfirmation();
                    break;

                case 'customs_clearance_update':
                    $this->sendCustomsUpdate();
                    break;

                case 'quote_ready':
                    $this->sendQuoteReady();
                    break;

                case 'welcome_email':
                    $this->sendWelcomeEmail();
                    break;

                default:
                    $this->sendGenericNotification();
            }

            Log::channel('notifications')->info("Notification sent successfully", [
                'user_id' => $this->user->id,
                'type' => $this->notificationType,
            ]);

        } catch (\Exception $e) {
            Log::channel('notifications')->error("Failed to send notification", [
                'user_id' => $this->user->id,
                'type' => $this->notificationType,
                'error' => $e->getMessage(),
            ]);

            throw $e; // Re-throw to trigger retry
        }
    }

    /**
     * Handle job failure
     */
    public function failed(\Throwable $exception): void
    {
        Log::channel('notifications')->error("Notification job failed permanently", [
            'user_id' => $this->user->id,
            'type' => $this->notificationType,
            'error' => $exception->getMessage(),
        ]);

        // Optionally notify admin about failed notification
    }

    protected function sendShipmentStatusUpdate(): void
    {
        $this->user->notify(new \App\Notifications\ShipmentStatusNotification($this->data));
    }

    protected function sendBookingConfirmation(): void
    {
        // Mail::to($this->user->email)->send(new BookingConfirmationMail($this->data));
    }

    protected function sendCustomsUpdate(): void
    {
        // Implementation
    }

    protected function sendQuoteReady(): void
    {
        // Implementation
    }

    protected function sendWelcomeEmail(): void
    {
        // Implementation
    }

    protected function sendGenericNotification(): void
    {
        // Fallback implementation
    }
}
