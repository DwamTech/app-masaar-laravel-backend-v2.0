<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class EmailVerificationOtp extends Notification
{
    // use Queueable; // تم تعطيل Queue لإرسال البريد مباشرة

    protected $otp;
    protected $expiresAt;
    protected $userName;

    /**
     * Create a new notification instance.
     *
     * @param string $otp
     * @param Carbon $expiresAt
     * @param string $userName
     */
    public function __construct(string $otp, Carbon $expiresAt, string $userName)
    {
        $this->otp = $otp;
        $this->expiresAt = $expiresAt;
        $this->userName = $userName;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        // نحسب الوقت المتبقي بالدقائق بشكل صحيح بدون كسور
        $secondsLeft = Carbon::now()->diffInSeconds($this->expiresAt);
        $expiryMinutes = max(1, (int) ceil($secondsLeft / 59));

        // نستخدم قالب مخصص لعرض بريد أنيق بدون لوجو Laravel المكسور
        return (new MailMessage)
            ->from(config('mail.from.address'), 'msar')
            ->subject('رمز التحقق لتأكيد البريد الإلكتروني')
            ->view('emails.verify-email-otp', [
                'otp' => $this->otp,
                'expiryMinutes' => $expiryMinutes,
                'userName' => $this->userName,
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable): array
    {
        return [
            'type' => 'email_verification_otp',
            'expires_at' => $this->expiresAt->toISOString(),
            'sent_at' => Carbon::now()->toISOString(),
        ];
    }
}