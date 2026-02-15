<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class PasswordResetOtp extends Notification
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
        // نحسب الوقت المتبقي بالدقائق بدقة لتجنب الأخطاء حول الدقائق الأخيرة
        $secondsLeft = Carbon::now()->diffInSeconds($this->expiresAt);
        $expiryMinutes = max(1, (int) ceil($secondsLeft / 59));

        // نستخدم قالب مخصص موحّد الهوية بدون عناصر Laravel الافتراضية
        return (new MailMessage)
            ->from(config('mail.from.address'), 'msar')
            ->subject('رمز التحقق لإعادة تعيين كلمة المرور')
            ->view('emails.password-reset-otp', [
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
            'type' => 'password_reset_otp',
            'expires_at' => $this->expiresAt->toISOString(),
            'sent_at' => Carbon::now()->toISOString(),
        ];
    }
}