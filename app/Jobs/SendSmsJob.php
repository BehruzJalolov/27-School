<?php

namespace App\Jobs;

use App\Contracts\SmsGateway;

class SendSmsJob
{
    public function __construct(
        public string $phone,
        public string $message,
    ) {}

    public function handle(SmsGateway $smsGateway): void
    {
        $smsGateway->send($this->phone, $this->message);
    }
}
