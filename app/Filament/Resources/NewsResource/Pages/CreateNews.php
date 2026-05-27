<?php
namespace App\Filament\Resources\NewsResource\Pages;
use App\Filament\Resources\NewsResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
class CreateNews extends CreateRecord {
    protected static string $resource = NewsResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array {
        $data['user_id'] = Auth::id();
        return $data;
    }
    protected function afterCreate(): void
    {
        $news = $this->record;

        // Отправлять уведомления только опубликованным новостям
        if (!$news->is_published) {
            return;
        }

        // Найти всех подписчиков на новости
        $subscribers = \App\Models\User::where('news_subscribed', true)
            ->where('id', '!=', auth()->id()) // не отправлять самому себе
            ->get();

        foreach ($subscribers as $subscriber) {
            $subscriber->notify(new \App\Notifications\NewsPublished($news));
        }
    }
}
