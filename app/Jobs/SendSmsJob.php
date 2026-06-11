<?php

namespace App\Jobs;

use App\Contracts\SmsGateway;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendSmsJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public function __construct(
        public string $phone,
        public string $message,
    ) {}

    public function handle(SmsGateway $smsGateway): void
    {
        $smsGateway->send($this->phone, $this->message);
    }
}
