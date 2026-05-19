<?php
// app/Filament/Resources/NewsResource/Pages/CreateNews.php
namespace App\Filament\Resource\NewsResource\Pages;

use App\Filament\Resource\NewsResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateNews extends CreateRecord
{
    protected static string $resource = NewsResource::class;

    // Автоматически ставим автора — текущего администратора
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id();
        return $data;
    }
}
