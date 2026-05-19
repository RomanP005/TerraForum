<?php
// app/Filament/Resources/NewsResource/Pages/EditNews.php
namespace App\Filament\Resource\NewsResource\Pages;

use App\Filament\Resource\NewsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNews extends EditRecord
{
    protected static string $resource = NewsResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
