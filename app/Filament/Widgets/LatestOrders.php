<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestOrders extends BaseWidget
{
    protected static ?string $heading = 'Dernières commandes';
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Order::query()->with(['user', 'items.course'])->latest()->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('#'),
                Tables\Columns\TextColumn::make('user.name')->label('Client')->placeholder('Non inscrit'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('items.course.title')->label('Formation')->limit(30),
                Tables\Columns\TextColumn::make('amount')->label('Montant')->money('eur'),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Statut')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'paid',
                        'danger' => fn ($state) => in_array($state, ['refunded', 'failed']),
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->paginated(false);
    }
}
