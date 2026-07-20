<?php

namespace App\Filament\Resources\BranchContentResource\Pages;

use App\Filament\Resources\BranchContentResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageBranchContents extends ManageRecords
{
    protected static string $resource = BranchContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
