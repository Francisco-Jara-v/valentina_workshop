<?php

namespace App\Filament\Resources\Notas\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class NotaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informacion')
                    ->schema([
                        TextInput::make('titulo')
                            ->label('Título')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('subtitulo')
                            ->label('Subtítulo')
                            ->maxLength(255),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                Section::make('Categoría')
                    ->schema([
                        Select::make('categoria_id')
                            ->label('Categoría')
                            ->relationship('categoria', 'nombre')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                TextInput::make('nombre')
                                    ->label('Nombre')
                                    ->required()
                                    ->maxLength(255),
                                Textarea::make('descripcion')
                                    ->label('Descripción')
                                    ->maxLength(65535),
                            ])
                            ->required(),
                    ])
                    ->columns(1)
                    ->columnSpanFull(),

                Section::make('Contenido')
                    ->schema([
                        RichEditor::make('contenido')
                            ->label('Contenido')
                            ->required()
                            ->extraAttributes([
                                'style' => 'min-height: 300px;',
                            ]),
                    ])
                    ->columns(1)
                    ->columnSpanFull(),
                
                
            ]);
    }
}
