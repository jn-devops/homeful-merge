<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DocumentMatrixResource\Pages;
use App\Filament\Resources\DocumentMatrixResource\RelationManagers;
use App\Models\DocumentMatrix;
use App\Models\Template;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Homeful\Contacts\Enums\CivilStatus;
use Homeful\Contacts\Enums\EmploymentStatus;
class DocumentMatrixResource extends Resource
{
    protected static ?string $model = DocumentMatrix::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Select::make('civil_status')
                    ->required()
                    ->options(collect(CivilStatus::cases())->mapWithKeys(fn($cs) => [$cs->value => $cs->value])->toArray())
                    ->native(false),
                Forms\Components\Select::make('employment_status')
                    ->required()
                    ->options(collect(EmploymentStatus::cases())->mapWithKeys(fn($cs) => [$cs->value => $cs->value])->toArray())
                    ->native(false),
                Forms\Components\TextInput::make('market_segment')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('documents')
                    ->multiple()
                    ->options(Template::all()->pluck('name', 'code'))
                    ->searchable()
                    ->native(false)
                    ->columnSpanFull()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('documents')
                    ->searchable(),
                Tables\Columns\TextColumn::make('civil_status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('employment_status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('market_segment')
                    ->searchable(),
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
            'index' => Pages\ManageDocumentMatrices::route('/'),
        ];
    }
}
