<?php

namespace App\Filament\Resources\Scientists\RelationManagers;

use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Actions\BulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\DissociateBulkAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ForceDeleteBulkAction;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class DocumentsRelationManager extends RelationManager
{
    protected static string $relationship = 'documents';

    public function form(Schema $schema): Schema
    {
        return $schema
           ->schema([
                Select::make('document_type')
                    ->options([
                        'cv' => 'CV/Resume',
                        'hcpc_registration' => 'HCPC/AHCS Registration',
                        'enhanced_dbs' => 'Enhanced DBS/CRB Check',
                        'immunisation_record' => 'Immunisation Record',
                        'bls_certificate' => 'BLS Certificate',
                        'health_safety_certification' => 'Health & Safety Certification',
                        'professional_indemnity_insurance' => 'Professional Indemnity Insurance',
                        'avs_cpd_updates' => 'AVS/CPD Updates',
                        'signed_contract' => 'Signed Contract',
                    ])
                    ->required(),
                TextInput::make('document_name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('document_number')
                    ->maxLength(255),
                DatePicker::make('issue_date')
                    ->required(),
                DatePicker::make('expiry_date'),
                TextInput::make('issuing_authority')
                    ->maxLength(255),
                FileUpload::make('document_file')
                    ->acceptedFileTypes([
                        'application/pdf',
                        'image/jpeg',
                        'image/png',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    ])
                    ->maxSize(10240), // 10MB
                Toggle::make('is_verified')
                    ->default(false),
                Textarea::make('verification_notes')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
           ->recordTitleAttribute('document_name')
            ->columns([
                TextColumn::make('document_type')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match($state) {
                        'cv' => 'CV',
                        'hcpc_registration' => 'HCPC Registration',
                        'enhanced_dbs' => 'DBS Check',
                        'immunisation_record' => 'Immunisation',
                        'bls_certificate' => 'BLS Certificate',
                        'health_safety_certification' => 'Health & Safety',
                        'professional_indemnity_insurance' => 'Insurance',
                        'avs_cpd_updates' => 'CPD Updates',
                        'signed_contract' => 'Contract',
                        default => $state,
                    })
                    ->color(fn ($state) => match($state) {
                        'cv' => 'primary',
                        'hcpc_registration' => 'success',
                        'signed_contract' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('document_name')
                    ->searchable(),
                TextColumn::make('issue_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('expiry_date')
                    ->date()
                    ->sortable(),
                IconColumn::make('is_verified')
                    ->boolean()
                    ->label('Verified'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->headerActions([
                CreateAction::make(),
                AssociateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
                Action::make('verify')
                    ->action(function ($record) {
                        $record->update(['is_verified' => true, 'verified_at' => now()]);
                    })
                    ->requiresConfirmation()
                    ->color('success')
                    ->icon('heroicon-o-check-badge')
                    ->hidden(fn ($record) => $record->is_verified),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    BulkAction::make('verify')
                        ->action(function ($records) {
                            $records->each->update(['is_verified' => true, 'verified_at' => now()]);
                        })
                        ->requiresConfirmation()
                        ->color('success')
                        ->icon('heroicon-o-check-badge'),
                ]),
            ])
            ->recordActions([
                EditAction::make(),
                DissociateAction::make(),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ]));
    }
}
