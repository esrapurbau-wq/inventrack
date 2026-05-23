<?php

namespace App\Filament\Resources\Items;

use App\Filament\Resources\Items\Pages\CreateItem;
use App\Filament\Resources\Items\Pages\EditItem;
use App\Filament\Resources\Items\Pages\ListItems;
use App\Filament\Resources\Items\Schemas\ItemForm;
use App\Filament\Resources\Items\Tables\ItemsTable;
use App\Models\Item;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Hidden;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Item';
 public static function form(Schema $schema): Schema
{
    return $schema
        ->schema([
            TextInput::make('nama_barang')
                ->label('Nama Barang')
                ->placeholder('Contoh: Laptop Lenovo ThinkPad')
                ->required()
                ->maxLength(255),

            TextInput::make('kode_barang')
                ->label('Kode Barang')
                ->placeholder('Contoh: BRG-001')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(255),

            TextInput::make('stok')
                ->label('Jumlah Stok')
                ->numeric()
                ->required()
                ->minValue(0),

            TextInput::make('harga')
                ->label('Harga Satuan (Rp)')
                ->numeric()
                ->required()
                ->minValue(0)
                ->prefix('Rp'),

            Select::make('kondisi')
                ->label('Kondisi Barang')
                ->options([
                    'Baik' => 'Baik',
                    'Rusak Ringan' => 'Rusak Ringan',
                    'Rusak Berat' => 'Rusak Berat',
                ])
                ->required(),

            Select::make('lokasi')
                ->label('Lokasi Penyimpanan')
                ->options([
                    'Gudang A' => 'Gudang A',
                    'Gudang B' => 'Gudang B',
                    'Gudang C' => 'Gudang C',
                ])
                ->required(),

            Textarea::make('deskripsi')
                ->label('Deskripsi Barang')
                ->placeholder('Jelaskan detail barang ini')
                ->required()
                ->rows(3),

            FileUpload::make('image')
                ->label('Foto Barang')
                ->image()
                ->directory('items')
                ->visibility('public')
                ->required(),

            Hidden::make('users_id'),
        ]);
}

    public static function table(Table $table): Table
{
    return $table
        ->columns([
            ImageColumn::make('image')
                ->label('Foto')
                ->disk('public'),

            TextColumn::make('kode_barang')
                ->label('Kode')
                ->searchable()
                ->sortable(),

            TextColumn::make('nama_barang')
                ->label('Nama Barang')
                ->searchable()
                ->sortable(),

            TextColumn::make('stok')
                ->label('Stok')
                ->sortable(),

            TextColumn::make('harga')
                ->label('Harga')
                ->money('IDR')
                ->sortable(),

            TextColumn::make('kondisi')
                ->label('Kondisi')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'Baik' => 'success',
                    'Rusak Ringan' => 'warning',
                    'Rusak Berat' => 'danger',
                    default => 'gray',
                }),

            TextColumn::make('lokasi')
                ->label('Lokasi')
                ->badge(),

            TextColumn::make('user.name')
                ->label('Ditambahkan Oleh'),

            TextColumn::make('created_at')
                ->label('Tanggal')
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
            'index' => ListItems::route('/'),
            'create' => CreateItem::route('/create'),
            'edit' => EditItem::route('/{record}/edit'),
        ];
    }
}
