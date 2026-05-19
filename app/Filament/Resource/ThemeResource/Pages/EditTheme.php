<?php
// app/Filament/Resources/ThemeResource/Pages/EditTheme.php
namespace App\Filament\Resource\ThemeResource\Pages;

use App\Filament\Resource\ThemeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTheme extends EditRecord
{
    protected static string $resource = ThemeResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
