<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Schemas\Components\Section;

use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class DashboardSettings extends Page implements HasForms

{

    use InteractsWithForms;

    protected static string|\BackedEnum|null $navigationIcon =
        'heroicon-o-cog-6-tooth';

    protected  string $view =
        'filament.pages.dashboard-settings';

    protected static ?string $navigationLabel =
        'Configuración Dashboard';

    protected static ?string $title =
        'Configuración Dashboard';

    public ?array $data = [];

    public function mount(): void
    {
        $this->data =
            Setting::getSetting(
                'dashboard',
                [
                    'widgets' => [
                        'resumen_financiero' => true,
                        'pedidos' => true,
                        'produccion' => true,
                    ],
                ]
            );
            $this->form->fill($this->data);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
    
                Section::make('Widgets Dashboard')
                    ->schema([
    
                        Toggle::make(
                            'widgets.resumen_financiero'
                        )
                            ->label(
                                'Resumen Financiero'
                            ),
    
                        Toggle::make(
                            'widgets.pedidos'
                        )
                            ->label(
                                'Pedidos'
                            ),
    
                        Toggle::make(
                            'widgets.produccion'
                        )
                            ->label(
                                'Producción'
                            ),
                    ]),
            ]);
    }

    public function save(): void
    {
        Setting::setSetting(
            'dashboard',
            $this->data
        );

        Notification::make()
            ->title('Configuración guardada')
            ->success()
            ->send();
    }
}