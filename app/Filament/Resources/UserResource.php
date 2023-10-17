<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;


class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    protected static ?string $navigationGroup = 'Administration';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('User Information')
                    ->icon('heroicon-m-shopping-bag')
                    ->description('Update the user\'s information.')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->autofocus()
                            ->required()
                            ->maxLength(255)
                            ->placeholder('John Doe'),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Toggle::make('active')
                            ->default(true),
                        Forms\Components\Toggle::make('is_admin')
                            ->required(),
                    ]),

                Forms\Components\Section::make('Security Information')
                    ->icon('heroicon-o-lock-closed')
                    ->description('Update the user\'s information.')
                    ->schema([
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->required()
                            ->confirmed(),
                        Forms\Components\TextInput::make('password_confirmation')
                            ->password()
                            ->label('Confirm Password')

                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                tables\Columns\ImageColumn::make('avatar')->circular(),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')->searchable()->sortable(),
                //   Tables\Columns\IconColumn::make('active')->boolean(),
                Tables\Columns\ToggleColumn::make('active')->label('Edit Active'),
                Tables\Columns\IconColumn::make('is_admin')->boolean()->label('Admin'),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d/m/y H:i'),
                Tables\Columns\TextColumn::make('updated_at')->dateTime('d/m/y H:i'),

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('active')
                    ->options([

                        true => 'Active',
                        false => 'Inactive',
                    ])
                    ->default(true)
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('Desactivate')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->icon('heroicon-o-x-circle')
                        ->action(function (Collection $users) {

                            if ($users->count() > 70) {

                                Notification::make()
                                    ->title('Muitos registros afetados')
                                    ->warning()
                                    ->send();

                                return false;
                            }
                            return $users->each->update(['active' => false]);
                        })
                        ->after(fn () => Notification::make()
                            ->title('Saved Successfully')
                            ->success()
                            ->send()),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
