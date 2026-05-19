<?php
// app/Filament/Resources/ThemeResource/Pages/ListThemes.php
namespace App\Filament\Resource\ThemeResource\Pages;

use App\Filament\Resource\ThemeResource;
use Filament\Resources\Pages\ListRecords;

class ListThemes extends ListRecords
{
    protected static string $resource = ThemeResource::class;
}
