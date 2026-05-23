<?php

namespace App\Filament\Resources\Suppliers;

use App\Filament\Resources\Suppliers\Pages\CreateSupplier;
use App\Filament\Resources\Suppliers\Pages\EditSupplier;
use App\Filament\Resources\Suppliers\Pages\ListSuppliers;
use App\Filament\Resources\Suppliers\Schemas\SupplierForm;
use App\Filament\Resources\Suppliers\Tables\SuppliersTable;
use App\Models\Supplier;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Supplier';

   public static function form(Schema $schema): Schema
{
    return $schema
        ->components([
            TextInput::make('nama_perusahaan')
                ->label('Nama Perusahaan')
                ->placeholder('Contoh: PT. Sumber Makmur')
                ->required()
                ->maxLength(255),

            TextInput::make('nama_kontak')
                ->label('Nama Contact Person')
                ->placeholder('Contoh: Budi Santoso')
                ->required()
                ->maxLength(255),

            TextInput::make('telepon')
                ->label('Nomor Telepon')
                ->placeholder('Contoh: 08123456789')
                ->required()
                ->maxLength(15),

            TextInput::make('email')
                ->label('Email')
                ->email()
                ->placeholder('Contoh: supplier@email.com')
                ->required()
                ->maxLength(255),

            Textarea::make('alamat')
                ->label('Alamat Lengkap')
                ->placeholder('Jl. Contoh No. 123, Kota, Provinsi')
                ->required()
                ->rows(3),

            FileUpload::make('image')
                ->label('Logo Perusahaan')
                ->image()
                ->directory('suppliers')
                ->visibility('public')
                ->required(),
        ]);
}
public static function table(Table $table): Table
{
    return $table
        ->columns([
            ImageColumn::make('image')
                ->label('Logo')
                ->disk('public'),

            TextColumn::make('nama_perusahaan')
                ->label('Perusahaan')
                ->searchable()
                ->sortable(),

            TextColumn::make('nama_kontak')
                ->label('Contact Person')
                ->searchable(),

            TextColumn::make('telepon')
                ->label('Telepon'),

            TextColumn::make('email')
                ->label('Email'),

            TextColumn::make('created_at')
                ->label('Ditambahkan')
                ->dateTime('d M Y')
                ->sortable(),
        ])
        ->filters([
            //
        ])
        ->actions([
            EditAction::make(),
            DeleteAction::make(),
        ])
        ->bulkActions([
            BulkActionGroup::make([
            DeleteBulkAction::make(),
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
            'index' => ListSuppliers::route('/'),
            'create' => CreateSupplier::route('/create'),
            'edit' => EditSupplier::route('/{record}/edit'),
        ];
    }
}
