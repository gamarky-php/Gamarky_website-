<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmailCommand extends Command
{
    protected $signature = 'test:email {email=test@example.com}';
    protected $description = 'Test email configuration by sending a test email';

    public function handle()
    {
        $email = $this->argument('email');
        
        $this->info("Sending test email to: {$email}");

        try {
            Mail::raw('This is a test email from Gamarky platform. If you received this, your email configuration is working correctly!', function ($message) use ($email) {
                $message->to($email)
                        ->subject('Test Email - Gamarky Platform');
            });

            $this->info('✓ Email sent successfully!');
            $this->info('Check your Mailtrap inbox or mail logs.');
            
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('✗ Failed to send email: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
