<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TemplateResource\Pages;
use App\Filament\Resources\TemplateResource\RelationManagers;
use App\Livewire\DocumentPreviewComponent;
use App\Models\Approvers;
use App\Models\Companies;
use App\Models\Projects;
use App\Models\Template;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Livewire;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use PhpOffice\PhpWord\Style\Section;
use ValentinMorice\FilamentJsonColumn\FilamentJsonColumn;

class TemplateResource extends Resource
{
    protected static ?string $model = Template::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    Forms\Components\Section::make()->schema([

                        Forms\Components\TextInput::make('code')
                            ->maxLength(255)
                            ->required(),
                        Forms\Components\TextInput::make('name')
                            ->maxLength(255)
                            ->required(),
                        Forms\Components\TextInput::make('title')
                            ->maxLength(255)
                            ->required(),
                        Forms\Components\Textarea::make('remarks')
                            ->rows(10)
                            ->cols(10)
                            ->required(),
                        Forms\Components\TextInput::make('url')
                            ->url()
                            ->maxLength(255)
                            ->required(),
                        Forms\Components\Select::make('fields')
                            ->relationship('fields', 'name')
                            ->preload()
                            ->native(false)
                            ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->name} : {$record->type}")
                            ->multiple()
                            ->required(),
//                        Forms\Components\Select::make('company_code')
//                            ->label('Company')
//                            ->options(
//                                Companies::all()->mapWithKeys(function($company){
//                                    return [$company->code=>$company->description];
//                                })->toArray()
//                            )->native(false)
//                            ->multiple()
//                            ->required(),
//                        Forms\Components\Select::make('projects')
//                            ->label('Projects')
//                            ->options(
//                                Projects::all()->mapWithKeys(function($project){
//                                    return [$project->code=>$project->description];
//                                })->toArray()
//                            )->native(false)
//                            ->multiple()
//                            ->required(),
//                        Forms\Components\Select::make('type')
//                            ->label('Type')
//                            ->options(
//                                [
//                                    'WORD'=>'WORD',
//                                    'PDF'=>'PDF',
//                                ]
//                            )->native(false)
//                            ->required(),

                    ])->columns(1)->columnSpan(3),
                        FilamentJsonColumn::make('data')->columnSpan(9),
//                    Forms\Components\Section::make()->schema([
//
//                    ])->columnSpan(9)->columns(9),

                ])->columns(12)->columnSpanFull(),
                Livewire::make(DocumentPreviewComponent::class)
                    ->key(Carbon::now()->format('Y-m-d H:i:s'))
                    ->columnSpanFull(),
//                Livewire::make(DocumentPreviewComponent::class)
//                    ->key(Carbon::now()->format('Y-m-d H:i:s'))
//                    ->columnSpanFull(),
//                Forms\Components\Section::make()->schema([
//                    Livewire::make(DocumentPreviewComponent::class)
//                        ->key(Carbon::now()->format('Y-m-d H:i:s'))
//                        ->columnSpanFull()
//                ])->columnSpan(8),
            ])->columns(12);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('url')
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
            'index' => Pages\ListTemplates::route('/'),
            'create' => Pages\CreateTemplate::route('/create'),
            'edit' => Pages\EditTemplate::route('/{record}/edit'),
        ];
    }
}
