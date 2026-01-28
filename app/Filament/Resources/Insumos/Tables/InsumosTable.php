<?php

namespace App\Filament\Resources\Insumos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class InsumosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')
                    ->searchable(),
                TextColumn::make('cantidad')
                    ->numeric()
                    ->color(fn ($record) =>
                        $record->stockBajo() ? 'danger' : 'success'
                    )
                    ->sortable(),
                TextColumn::make('precio_unitario')
                    ->numeric()
                    ->sortable()
                    ->money('CLP')
                    ->badge(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            
            /*->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])*/;
    }
}
