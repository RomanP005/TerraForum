<?php
// app/Filament/Resources/UserResource/Pages/ListUsers.php
namespace App\Filament\Resource\UserResource\Pages;
use App\Filament\Resource\UserResource;
use Filament\Resources\Pages\ListRecords;
class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;
}
