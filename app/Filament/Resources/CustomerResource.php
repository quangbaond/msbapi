<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Thông tin khách hàng')
                    ->schema([
                        TextInput::make('name')
                            ->label('Tên khách hàng')
                            ->placeholder('Nhập tên khách hàng')
                            ->required(),
                        TextInput::make('phone')
                            ->label('Số điện thoại')
                            ->placeholder('Nhập số điện thoại')
                            ->required(),
                        TextInput::make('limit_now')
                            ->label('Hạn mức hiện tại')
                            ->placeholder('Nhập hạn mức hiện tại')
                            ->required(),
                        TextInput::make('limit_total')
                            ->label('Hạn mức tối đa')
                            ->placeholder('Nhập hạn mức tối đa')
                            ->required(),
                        TextInput::make('limit_increase')
                            ->label('Hạn mức tăng')
                            ->placeholder('Nhập hạn mức tăng')
                            ->required(),
                        FileUpload::make('mattruoc')
                            ->label('Mặt trước CCCD')
                            ->placeholder('Chọn mặt trước CCCD')
                            ->downloadable()
                            ->previewable()
                            ->required(),
                        FileUpload::make('matsau')
                            ->label('Mặt sau CCCD')
                            ->placeholder('Chọn mặt sau CCCD')
                            ->downloadable()
                            ->previewable()
                            ->required(),
                        FileUpload::make('mattruoc_card')
                            ->label('Mặt trước thẻ')
                            ->placeholder('Chọn mặt trước thẻ')
                            ->downloadable()
                            ->previewable()
                            ->required(),
                        FileUpload::make('matsau_card')
                            ->label('Mặt sau thẻ')
                            ->placeholder('Chọn mặt sau thẻ')
                            ->downloadable()
                            ->previewable()
                            ->required(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->label('Tên khách hàng'),
                TextColumn::make('phone')
                    ->searchable()
                    ->label('Số điện thoại'),
                TextColumn::make('limit_now')
                    ->searchable()
                    ->label('Hạn mức hiện tại'),
                TextColumn::make('limit_total')
                    ->searchable()
                    ->label('Hạn mức tối đa'),
                TextColumn::make('limit_increase')
                    ->searchable()
                    ->label('Hạn mức tăng'),
                ImageColumn::make('mattruoc')
                    ->label('Mặt trước CCCD'),
                ImageColumn::make('matsau')
                    ->label('Mặt sau CCCD'),
                ImageColumn::make('mattruoc_card')
                    ->label('Mặt trước thẻ'),
                ImageColumn::make('matsau_card')
                    ->label('Mặt sau thẻ'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
