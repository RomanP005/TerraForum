<?php
// app/Filament/Resources/UserResource/Pages/EditUser.php
namespace App\Filament\Resource\UserResource\Pages;
use App\Filament\Resource\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;
    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
