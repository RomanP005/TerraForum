<?php
namespace App\Notifications;
use App\Models\News;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewsPublished extends Notification
{
    use Queueable;

    public function __construct(public News $news) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'    => 'news',
            'title'   => 'Новая статья на платформе',
            'message' => 'Опубликована новая статья: «' . $this->news->title . '»',
            'url'     => route('news.show', $this->news->slug),
            'news_id' => $this->news->id,
        ];
    }
}
