<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Models\Payment;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationGroup = 'Quản lý người dùng';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Thanh toán';

    protected static ?string $pluralModelLabel = 'Thanh toán';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Thông tin thanh toán')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Người dùng')
                            ->options(User::pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('gateway')
                            ->label('Cổng thanh toán')
                            ->options([
                                'momo' => 'MoMo',
                                'vnpay' => 'VNPay',
                                'bank_transfer' => 'Chuyển khoản ngân hàng',
                                'cash' => 'Tiền mặt',
                            ])
                            ->searchable()
                            ->required(),
                        Forms\Components\DateTimePicker::make('transaction_date')
                            ->label('Ngày giao dịch')
                            ->required(),
                        Forms\Components\TextInput::make('account_number')
                            ->label('Số tài khoản')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('code')
                            ->label('Mã giao dịch')
                            ->maxLength(255),
                        Forms\Components\Textarea::make('content')
                            ->label('Nội dung')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\Select::make('transfer_type')
                            ->label('Loại chuyển khoản')
                            ->options([
                                'in' => 'Nhận tiền',
                                'out' => 'Chuyển tiền',
                            ]),
                        Forms\Components\TextInput::make('transfer_amount')
                            ->label('Số tiền chuyển')
                            ->numeric()
                            ->prefix('đ')
                            ->minValue(0)
                            ->required(),
                        Forms\Components\TextInput::make('accumulated')
                            ->label('Số dư tích lũy')
                            ->numeric()
                            ->prefix('đ')
                            ->minValue(0),
                        Forms\Components\TextInput::make('sub_account')
                            ->label('Tài khoản phụ')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('reference_code')
                            ->label('Mã tham chiếu')
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->label('Mô tả')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\Select::make('status')
                            ->label('Trạng thái')
                            ->options([
                                'pending' => 'Chờ xử lý',
                                'completed' => 'Hoàn thành',
                                'failed' => 'Thất bại',
                                'cancelled' => 'Đã hủy',
                            ])
                            ->default('pending')
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Dữ liệu thô')
                    ->schema([
                        Forms\Components\KeyValue::make('raw_payload')
                            ->label('Dữ liệu webhook')
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Người dùng')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('gateway')
                    ->label('Cổng thanh toán')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'momo' => 'success',
                        'vnpay' => 'info',
                        'bank_transfer' => 'warning',
                        'cash' => 'gray',
                        default => 'primary',
                    }),
                Tables\Columns\TextColumn::make('transaction_date')
                    ->label('Ngày giao dịch')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('transfer_amount')
                    ->label('Số tiền')
                    ->money('vnd', true)
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Trạng thái')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'completed',
                        'danger' => 'failed',
                        'gray' => 'cancelled',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Chờ xử lý',
                        'completed' => 'Hoàn thành',
                        'failed' => 'Thất bại',
                        'cancelled' => 'Đã hủy',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('code')
                    ->label('Mã GD')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tạo lúc')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'pending' => 'Chờ xử lý',
                        'completed' => 'Hoàn thành',
                        'failed' => 'Thất bại',
                        'cancelled' => 'Đã hủy',
                    ]),
                Tables\Filters\SelectFilter::make('gateway')
                    ->label('Cổng thanh toán')
                    ->options([
                        'momo' => 'MoMo',
                        'vnpay' => 'VNPay',
                        'bank_transfer' => 'Chuyển khoản ngân hàng',
                        'cash' => 'Tiền mặt',
                    ]),
                Tables\Filters\Filter::make('transaction_date')
                    ->form([
                        Forms\Components\DatePicker::make('transaction_from')
                            ->label('Từ ngày'),
                        Forms\Components\DatePicker::make('transaction_until')
                            ->label('Đến ngày'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['transaction_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('transaction_date', '>=', $date),
                            )
                            ->when(
                                $data['transaction_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('transaction_date', '<=', $date),
                            );
                    }),
                Tables\Filters\Filter::make('amount_range')
                    ->form([
                        Forms\Components\TextInput::make('min_amount')
                            ->label('Số tiền tối thiểu')
                            ->numeric()
                            ->prefix('đ'),
                        Forms\Components\TextInput::make('max_amount')
                            ->label('Số tiền tối đa')
                            ->numeric()
                            ->prefix('đ'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['min_amount'],
                                fn (Builder $query, $amount): Builder => $query->where('transfer_amount', '>=', $amount),
                            )
                            ->when(
                                $data['max_amount'],
                                fn (Builder $query, $amount): Builder => $query->where('transfer_amount', '<=', $amount),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('mark_completed')
                        ->label('Đánh dấu hoàn thành')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (Payment $record) {
                            $record->update(['status' => 'completed']);
                        })
                        ->visible(fn (Payment $record) => $record->status !== 'completed'),
                    Tables\Actions\Action::make('mark_failed')
                        ->label('Đánh dấu thất bại')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function (Payment $record) {
                            $record->update(['status' => 'failed']);
                        })
                        ->visible(fn (Payment $record) => $record->status !== 'failed'),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('mark_completed')
                        ->label('Đánh dấu hoàn thành')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                $record->update(['status' => 'completed']);
                            });
                        }),
                    Tables\Actions\BulkAction::make('mark_failed')
                        ->label('Đánh dấu thất bại')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                $record->update(['status' => 'failed']);
                            });
                        }),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('transaction_date', 'desc');
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
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('user');
    }
}
