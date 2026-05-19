<?php

namespace App\Notifications;

use App\Models\WaiterSalaryPayment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SalaryPaymentConfirmed extends Notification
{
    use Queueable;

    public function __construct(
        public WaiterSalaryPayment $payment
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'salary_payment_confirmed',
            'period_month' => $this->payment->period_month,
            'period_label' => $this->payment->period_label,
            'message' => 'Your payment for '.$this->payment->period_label.' has been confirmed – view your salary slip.',
            'url' => route('waiter.salary-slip.show', $this->payment->period_month),
        ];
    }
}
