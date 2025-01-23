<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Flight;
use App\Models\Airport;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\FlightResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\FlightResource\RelationManagers;
use App\Models\Airline;

class FlightResource extends Resource
{
    protected static ?string $model = Flight::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Airline Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('flight_number')
                    ->required()
                    ->maxLength(255),
                Select::make('airline_id')
                    ->required()
                    ->options(function () {
                        return Airline::query()->pluck('name', 'id');
                    }),
                Select::make('departure_airport_id')
                    ->options(function () {
                        return Airport::query()->pluck('city', 'id');
                    })
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('arrival_airport_id')
                    ->options(function () {
                    return Airport::query()->pluck('city', 'id');
                    })
                    ->searchable()
                    ->preload()
                    ->required(),
                DateTimePicker::make('departure_time')
                    ->required(),
                DateTimePicker::make('arrival_time')
                    ->required(),
                TextInput::make('capacity')
                    ->required()
                    ->numeric(),
                TextInput::make('available_seats')
                    ->required()
                    ->numeric(),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('Rp'),
                Select::make('status')
                    ->options([
                        'scheduled' => 'Scheduled',
                        'delayed' => 'Delayed',
                        'cancelled' => 'Cancelled',
                        'completed' => 'Completed',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            Tables\Columns\TextColumn::make('flight_number')
                ->searchable(),
            Tables\Columns\TextColumn::make('airline.name')
                    ->sortable()
                    ->searchable(),
            Tables\Columns\TextColumn::make('departureAirport.city')
                ->label('Departure')
                ->searchable(),
            Tables\Columns\TextColumn::make('arrivalAirport.city')
                ->label('Arrival')
                ->searchable(),
            Tables\Columns\TextColumn::make('departure_time')
                ->dateTime()
                ->sortable(),
            Tables\Columns\TextColumn::make('arrival_time')
                ->dateTime()
                ->sortable(),
            Tables\Columns\TextColumn::make('available_seats')
                ->numeric()
                ->sortable(),
            Tables\Columns\TextColumn::make('price')
                ->money('idr')
                ->sortable(),
            BadgeColumn::make('status')
                ->colors([
                    'success' => 'completed',
                    'warning' => 'delayed',
                    'danger' => 'cancelled',
                    'primary' => 'scheduled',
                ]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'scheduled' => 'Scheduled',
                        'delayed' => 'Delayed',
                        'cancelled' => 'Cancelled',
                        'completed' => 'Completed',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFlights::route('/'),
            'create' => Pages\CreateFlight::route('/create'),
            'edit' => Pages\EditFlight::route('/{record}/edit'),
        ];
    }
}
