<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Flight;
use App\Models\Airport;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\FlightResource\Pages;
use Filament\Tables\Columns\TextColumn;
use App\Models\Airline;

class FlightResource extends Resource
{
    protected static ?string $model = Flight::class;

    protected static ?string $navigationIcon = 'heroicon-o-eye';
    protected static ?string $navigationGroup = 'Airline Management';
    protected static ?string $navigationLabel = 'Flights';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Flight Details')
                    ->description('Enter flight information')
                    ->schema([
                        TextInput::make('flight_number')
                            ->label('Flight Number')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(10)
                            ->placeholder('Enter flight number'),

                        Select::make('airline_id')
                            ->label('Airline')
                            ->relationship('airline', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('departure_airport_id')
                            ->label('Departure Airport')
                            ->relationship('departureAirport', 'city')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('arrival_airport_id')
                            ->label('Arrival Airport')
                            ->relationship('arrivalAirport', 'city')
                            ->searchable()
                            ->preload()
                            ->required(),

                        DateTimePicker::make('departure_time')
                            ->label('Departure Time')
                            ->required()
                            ->native(false),

                        DateTimePicker::make('arrival_time')
                            ->label('Arrival Time')
                            ->required()
                            ->native(false),

                        TextInput::make('capacity')
                            ->label('Total Capacity')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(1000),

                        TextInput::make('available_seats')
                            ->label('Available Seats')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(fn ($get) => $get('capacity')),

                        TextInput::make('price')
                            ->label('Ticket Price')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->minValue(0),

                        Select::make('status')
                            ->label('Flight Status')
                            ->options([
                                'scheduled' => 'Scheduled',
                                'delayed' => 'Delayed',
                                'cancelled' => 'Cancelled',
                                'completed' => 'Completed',
                            ])
                            ->required()
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('flight_number')
                    ->label('Flight Number')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('airline.name')
                    ->label('Airline')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('departureAirport.city')
                    ->label('Departure')
                    ->searchable(),

                TextColumn::make('arrivalAirport.city')
                    ->label('Arrival')
                    ->searchable(),

                TextColumn::make('departure_time')
                    ->label('Departure')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('arrival_time')
                    ->label('Arrival')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('available_seats')
                    ->label('Available Seats')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => $state <= 10 ? 'danger' : 'primary'),

                TextColumn::make('price')
                    ->label('Ticket Price')
                    ->money('idr')
                    ->sortable(),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'completed',
                        'warning' => 'delayed',
                        'danger' => 'cancelled',
                        'primary' => 'scheduled',
                    ]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('airline')
                    ->relationship('airline', 'name')
                    ->searchable(),

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'scheduled' => 'Scheduled',
                        'delayed' => 'Delayed',
                        'cancelled' => 'Cancelled',
                        'completed' => 'Completed',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('departure_time', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            // Tambahkan relation manager jika diperlukan
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
