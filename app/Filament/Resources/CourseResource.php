<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseResource\Pages;
use App\Models\Course;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Forms\Components\RichEditor;


class CourseResource extends Resource
{
    protected static ?string $model = Course::class;
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Contenu';
    protected static ?string $navigationLabel = 'Formations';
    protected static ?string $modelLabel = 'Formation';
    protected static ?string $pluralModelLabel = 'Formations';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Wizard::make([
                    Forms\Components\Wizard\Step::make('Informations')
                        ->icon('heroicon-o-information-circle')
                        ->schema([
                            Forms\Components\TextInput::make('title')
                                ->label('Titre')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn ($state, Forms\Set $set) =>
                                    $set('slug', Str::slug($state))
                                ),
                            Forms\Components\TextInput::make('slug')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(255),
                            Forms\Components\Select::make('category_id')
                                ->label('Catégorie')
                                ->relationship('category', 'name')
                                ->searchable()
                                ->preload()
                                ->createOptionForm([
                                    Forms\Components\TextInput::make('name')->required(),
                                    Forms\Components\TextInput::make('slug')->required(),
                                ]),
                            Forms\Components\Textarea::make('description')
                                ->label('Description courte')
                                ->rows(3)
                                ->maxLength(500),
                            Forms\Components\RichEditor::make('long_description')
                                ->label('Description longue')
                                ->columnSpanFull()
                                ->toolbarButtons([
                                     // Formatage de base
                                    'bold',
                                    'italic',
                                    'underline',
                                    'strike',
                                    
                                    // Couleurs
                                    'color',           // Couleur du texte
                                    'backgroundColor', // Fond du texte
                                    'highlight',       // Surlignage
                                    
                                    // Titres
                                    'h1',
                                    'h2',
                                    'h3',
                                    'h4',
                                    'h5',
                                    'h6',
                                    
                                    // Listes
                                    'bulletList',
                                    'orderedList',
                                    'taskList',        // Liste de tâches
                                    
                                    // Alignement
                                    'alignLeft',
                                    'alignCenter',
                                    'alignRight',
                                    'alignJustify',
                                    
                                    // Blocs
                                    'blockquote',
                                    'codeBlock',
                                    'paragraph',
                                    
                                    // Tableaux
                                    'table',
                                    
                                    // Liens et médias
                                    'link',
                                    'image',
                                    'media',
                                    'attachFiles',
                                    
                                    // Formatage avancé
                                    'subscript',
                                    'superscript',
                                    'small',
                                    'code',
                                    
                                    // Organisation
                                    'horizontalRule',
                                    'hardBreak',
                                    
                                    // Nettoyage
                                    'clean',
                                    
                                    // Historique
                                    'undo',
                                    'redo',
                                ]),
                            Forms\Components\FileUpload::make('thumbnail')
                                ->label('Image de couverture')
                                ->image()
                                ->directory('courses/thumbnails')
                                ->imageResizeMode('cover')
                                ->imageCropAspectRatio('16:9')
                                ->imageResizeTargetWidth('1280')
                                ->imageResizeTargetHeight('720'),
                            Forms\Components\TextInput::make('trailer_url')
                                ->label('URL du trailer')
                                ->url()
                                ->placeholder('https://youtube.com/watch?v=...'),
                        ])->columns(2),

                    Forms\Components\Wizard\Step::make('Prix & Publication')
                        ->icon('heroicon-o-currency-euro')
                        ->schema([
                            Forms\Components\TextInput::make('price')
                                ->label('Prix (€)')
                                ->required()
                                ->numeric()
                                ->prefix('€')
                                ->step(0.01),
                            Forms\Components\TextInput::make('sale_price')
                                ->label('Prix promo (€)')
                                ->numeric()
                                ->prefix('€')
                                ->step(0.01),
                            Forms\Components\Select::make('currency')
                                ->label('Devise')
                                ->options(['EUR' => 'Euro (€)', 'USD' => 'Dollar ($)', 'XOF' => 'CFA (FCFA)'])
                                ->default('EUR'),
                            Forms\Components\Select::make('status')
                                ->label('Statut')
                                ->options(['draft' => 'Brouillon', 'published' => 'Publié'])
                                ->default('draft')
                                ->required(),
                            Forms\Components\Toggle::make('is_featured')
                                ->label('Mise en avant')
                                ->helperText('Affiché en priorité sur la page d\'accueil'),
                            Forms\Components\TextInput::make('meta_title')
                                ->label('Meta titre (SEO)')
                                ->maxLength(70),
                            Forms\Components\Textarea::make('meta_description')
                                ->label('Meta description (SEO)')
                                ->rows(2)
                                ->maxLength(160),
                        ])->columns(2),

                    Forms\Components\Wizard\Step::make('Contenu')
                        ->icon('heroicon-o-play')
                        ->schema([
                            Forms\Components\Repeater::make('modules')
                                ->relationship()
                                ->label('Modules')
                                ->schema([
                                    Forms\Components\TextInput::make('title')
                                        ->label('Titre du module')
                                        ->required(),
                                    Forms\Components\Textarea::make('description')
                                        ->label('Description')
                                        ->rows(2),
                                    Forms\Components\TextInput::make('order')
                                        ->label('Ordre')
                                        ->numeric()
                                        ->default(0),
                                    Forms\Components\Repeater::make('lessons')
                                        ->relationship()
                                        ->label('Leçons')
                                        ->schema([
                                            Forms\Components\TextInput::make('title')
                                                ->label('Titre')
                                                ->required()
                                                ->columnSpan(2),
                                            Forms\Components\Select::make('type')
                                                ->label('Type de contenu')
                                                ->options([
                                                    'video' => '🎬 Vidéo',
                                                    'audio' => '🎧 Audio',
                                                    'pdf' => '📄 PDF',
                                                    'text' => '📝 Texte / Article',
                                                ])
                                                ->default('video')
                                                ->required()
                                                ->live()
                                                ->columnSpan(1),
                                            
                                            // Champs conditionnels
                                            Forms\Components\FileUpload::make('content_url')
                                                ->label('Fichier Vidéo')
                                                ->directory('courses/lessons/videos')
                                                ->visible(fn (Forms\Get $get) => $get('type') === 'video')
                                                ->required()
                                                ->columnSpanFull()
                                                ->acceptedFileTypes(['video/mp4', 'video/quicktime'])
                                                ->maxSize(512000), // 500MB
                                            
                                            Forms\Components\FileUpload::make('content_url')
                                                ->label('Fichier Audio')
                                                ->directory('courses/lessons/audio')
                                                ->visible(fn (Forms\Get $get) => $get('type') === 'audio')
                                                ->required()
                                                ->columnSpanFull()
                                                ->acceptedFileTypes(['audio/mpeg', 'audio/wav']),
                                            
                                            Forms\Components\FileUpload::make('content_url')
                                                ->label('Document PDF')
                                                ->directory('courses/lessons/pdf')
                                                ->visible(fn (Forms\Get $get) => $get('type') === 'pdf')
                                                ->required()
                                                ->columnSpanFull(),

                                            Forms\Components\RichEditor::make('content_text')
                                                ->label('Contenu de la leçon')
                                                ->helperText('Rédigez votre leçon comme dans Notion (Texte, Images, Vidéos intégrées...)')
                                                ->visible(fn (Forms\Get $get) => $get('type') === 'text')
                                                ->required()
                                                ->columnSpanFull()
                                                ->fileAttachmentsDirectory('courses/lessons/attachments')
                                                ->toolbarButtons([
                                                    'bold', 'italic', 'underline', 'strike', 'subscript', 'superscript', 'link',
                                                    'h2', 'h3', 'alignStart', 'alignCenter', 'alignEnd',
                                                    'blockquote', 'codeBlock', 'bulletList', 'orderedList',
                                                    'table', 'attachFiles',
                                                    'undo', 'redo',
                                                ]),

                                            Forms\Components\TextInput::make('duration_seconds')
                                                ->label('Durée (sec)')
                                                ->numeric()
                                                ->columnSpan(1),
                                            Forms\Components\TextInput::make('order')
                                                ->label('Ordre')
                                                ->numeric()
                                                ->default(0)
                                                ->columnSpan(1),
                                            Forms\Components\Toggle::make('is_preview')
                                                ->label('Aperçu gratuit')
                                                ->columnSpan(1),
                                        ])
                                        ->columns(3)
                                        ->reorderable()
                                        ->collapsible()
                                        ->cloneable()
                                        ->itemLabel(fn (array $state) => $state['title'] ?? 'Nouvelle leçon'),
                                ])
                                ->reorderable()
                                ->collapsible()
                                ->cloneable()
                                ->itemLabel(fn (array $state) => $state['title'] ?? 'Nouveau module'),
                        ]),
                ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->label('Image')
                    ->circular()
                    ->size(50),
                Tables\Columns\TextColumn::make('title')
                    ->label('Titre')
                    ->searchable()
                    ->sortable()
                    ->limit(40),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Catégorie')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Prix')
                    ->money('eur')
                    ->sortable(),
                Tables\Columns\TextColumn::make('sale_price')
                    ->label('Prix promo')
                    ->money('eur')
                    ->placeholder('—'),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Statut')
                    ->colors([
                        'warning' => 'draft',
                        'success' => 'published',
                    ])
                    ->formatStateUsing(fn ($state) => match($state) {
                        'draft' => 'Brouillon',
                        'published' => 'Publié',
                        default => $state,
                    }),
                Tables\Columns\IconColumn::make('is_featured')
                    ->label('★')
                    ->boolean(),
                Tables\Columns\TextColumn::make('enrollments_count')
                    ->label('Élèves')
                    ->counts('enrollments')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(['draft' => 'Brouillon', 'published' => 'Publié']),
                Tables\Filters\SelectFilter::make('category_id')
                    ->relationship('category', 'name')
                    ->label('Catégorie'),
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Mise en avant'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
