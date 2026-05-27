<?php
namespace App\Notifications;
use App\Models\Post;
use App\Models\Theme;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReplyToTheme extends Notification
{
    use Queueable;

    public function __construct(
        public Theme $theme,
        public Post  $post,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'     => 'reply',
            'title'    => 'Новый ответ на вашу тему',
            'message'  => 'Пользователь ' . $this->post->user->name
                . ' ответил на тему «' . $this->theme->title . '»',
            'url'      => route('forum.theme', $this->theme->slug) . '#post-' . $this->post->id,
            'theme_id' => $this->theme->id,
            'post_id'  => $this->post->id,
        ];
    }
}
