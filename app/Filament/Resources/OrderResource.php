<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationGroup = 'Ventes';
    protected static ?string $navigationLabel = 'Commandes';
    protected static ?string $modelLabel = 'Commande';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Détails de la commande')->schema([
                Forms\Components\TextInput::make('email')->disabled(),
                Forms\Components\TextInput::make('amount')->label('Montant')->disabled()->prefix('€'),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'En attente',
                        'paid' => 'Payé',
                        'refunded' => 'Remboursé',
                        'failed' => 'Échoué',
                    ]),
                Forms\Components\TextInput::make('stripe_session_id')->disabled()->label('Session Stripe'),
                Forms\Components\TextInput::make('stripe_payment_intent_id')->disabled()->label('Payment Intent'),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('#')->sortable(),
                Tables\Columns\TextColumn::make('user.name')->label('Client')->searchable()->placeholder('Non inscrit'),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('amount')->label('Montant')->money('eur')->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Statut')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'paid',
                        'danger' => fn ($state) => in_array($state, ['refunded', 'failed']),
                    ])
                    ->formatStateUsing(fn ($state) => match($state) {
                        'pending' => 'En attente',
                        'paid' => 'Payé',
                        'refunded' => 'Remboursé',
                        'failed' => 'Échoué',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('items.course.title')
                    ->label('Formation(s)')
                    ->limit(30),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Statut')
                    ->options([
                        'pending' => 'En attente',
                        'paid' => 'Payé',
                        'refunded' => 'Remboursé',
                        'failed' => 'Échoué',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'paid')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }
}
