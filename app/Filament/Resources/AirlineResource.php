<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Airline;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\AirlineResource\Pages;

class AirlineResource extends Resource
{
    protected static ?string $model = Airline::class;

    protected static ?string $navigationIcon = 'heroicon-o-paper-airplane';
    protected static ?string $navigationGroup = 'Airline Management';
    protected static ?string $navigationLabel = 'Airlines';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Airline Details')
                    ->description('Enter airline information')
                    ->schema([
                        TextInput::make('name')
                            ->label('Airline Name')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('Enter airline name'),

                        TextInput::make('code')
                            ->label('Airline Code')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(3)
                            ->placeholder('Enter 2-3 letter airline code'),

                        FileUpload::make('logo')
                            ->label('Airline Logo')
                            ->required()
                            ->image()
                            ->directory('airlines')
                            ->visibility('public')
                            ->maxSize(5120) // 5MB
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('1:1')
                            ->imageResizeTargetWidth('300')
                            ->imageResizeTargetHeight('300')
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Airline Name')
                    ->sortable()
                    ->searchable()
                    ->description(fn (Airline $record): string => $record->code),

                ImageColumn::make('logo')
                    ->label('Logo')
                    ->height(50),

                TextColumn::make('flights_count')
                    ->label('Total Flights')
                    ->counts('flights')
                    ->badge()
                    ->color('primary')
            ])
            ->filters([
                // Optional: Tambahkan filter jika diperlukan
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name');
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
            'index' => Pages\ListAirlines::route('/'),
            'create' => Pages\CreateAirline::route('/create'),
            'edit' => Pages\EditAirline::route('/{record}/edit'),
        ];
    }
}
