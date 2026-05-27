<?php
namespace App\Notifications;
use App\Models\Service;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ServiceContact extends Notification
{
    use Queueable;

    public function __construct(
        public Service $service,
        public User    $sender,
        public string  $message,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'       => 'service_contact',
            'title'      => 'Новый запрос по вашей услуге',
            'message'    => 'Пользователь ' . $this->sender->name
                . ' интересуется услугой «' . $this->service->title . '»: '
                . \Illuminate\Support\Str::limit($this->message, 100),
            'url'        => route('services.show', $this->service->slug),
            'service_id' => $this->service->id,
            'sender_id'  => $this->sender->id,
        ];
    }
}
