<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SetResource\Pages;
use App\Filament\Resources\SetResource\RelationManagers;
use App\Models\Set;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SetResource extends Resource
{
    protected static ?string $model = Set::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                    ])->columnSpan(1)->columns(1),
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Select::make('templates')
                            ->relationship('templates', 'name')
                            ->preload()
                            ->native(false)
                            ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->code} : {$record->name}")
                            ->multiple()
                            ->required(),
                    ])->columnSpan(2),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->grow(false)
                    ->searchable(),
                Tables\Columns\TextColumn::make('code')
                    ->grow(false)
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->grow(false)
                    ->searchable(),
                Tables\Columns\TextColumn::make('templates.name')
                    ->grow()
//                    ->listWithLineBreaks()
                    ->badge(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSets::route('/'),
        ];
    }
}
