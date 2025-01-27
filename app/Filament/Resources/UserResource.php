<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Hash;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
//use STS\FilamentImpersonate\Tables\Actions\Impersonate;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?int $navigationSort = 9;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }


    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                TextInput::make('name')
                    ->required()
                    ->columns(6),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->columns(6),
                TextInput::make('password')
                    ->password()
                    ->revealable()
                    ->maxLength(255)
                    ->dehydrateStateUsing(static function ($state) use ($form) {
                        return !empty($state)
                            ? Hash::make($state)
                            : User::find($form->getRecord()->id)?->password;
                    })->columns(6),
            ])->columns(2)->columnSpan(9),
            Section::make()->schema([
//                Forms\Components\Select::make('roles')
//                    ->preload()
//                    ->relationship('roles', 'name')
//                    ->native(false)
//                    ->columnSpanFull(),
                Placeholder::make('created')
                    ->content(fn ( $record): string =>$record==null?'': $record->created_at->toFormattedDateString()),
            ])->columnSpan(3),
        ])->columns(12);
    }
    public static function table(Table $table): Table
    {
//        if(class_exists( STS\FilamentImpersonate\Tables\Actions\Impersonate::class) && config('filament-users.impersonate')){
//            $table->actions([Impersonate::make('impersonate')]);
//        }
        $table
            ->columns([
                TextColumn::make('id')
                    ->sortable(),
                TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('email')
                    ->sortable()
                    ->searchable(),
//                IconColumn::make('email_verified_at')
//                    ->boolean()
//                    ->sortable()
//                    ->searchable()
//                    ->label(trans('filament-users::user.resource.email_verified_at')),
                TextColumn::make('created_at')
                    ->dateTime('M j, Y')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime('M j, Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('verified')
                    ->query(fn(Builder $query): Builder => $query->whereNotNull('email_verified_at')),
                Tables\Filters\Filter::make('unverified')
                    ->query(fn(Builder $query): Builder => $query->whereNull('email_verified_at')),
            ])
            ->actions([
//                Impersonate::make('impersonate'),
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
//                ActionGroup::make([
//                    ViewAction::make(),
//                    EditAction::make(),
//                    DeleteAction::make()
//                ]),
            ]);
        return $table;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
