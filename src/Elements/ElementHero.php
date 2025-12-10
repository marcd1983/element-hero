<?php

namespace Antlion\ElementHero\Elements;

use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\NumericField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\LinkField\Models\Link;
use SilverStripe\LinkField\Form\MultiLinkField;
use SilverStripe\Forms\FieldGroup;

class ElementHero extends BaseElement
{
    private static array $db = [
        'Title' => 'Varchar(255)',     // Headline
        'Subtitle' => 'Varchar(255)',     // Subheadline
        'Theme' => 'Enum("light,dark","dark")',
        'Height' => 'Enum("auto,short,medium,tall,full","tall")',
        'HorizontalAlign' => 'Enum("center,left,right","center")',
        'VerticalAlign' => "Enum('top,middle,bottom','middle')",
        'Padding' => "Enum('none,20px,40px,60px','none')",
        'OverlayOpacity' => 'Int',              // 0–100
        'Content' => 'Text',             // optional small blurb
    ];

    private static array $has_one = [
        'BackgroundImage' => Image::class,
    ];

    private static array $has_many = [
       'Links' => Link::class . '.Owner',
    ];

    private static array $defaults = [
        'HorizontalAlign' => 'center',
        'VerticalAlign' => 'middle',
        'Padding' => '20px',
    ];

    private static array $owns = [
        'BackgroundImage',
        'Links',
    ];

    private static string $icon = 'font-icon-block-banner';
    private static string $table_name = 'ElementHero';

    public function getType(): string
    {
        return 'Hero';
    }

    public function getCMSFields(): FieldList
    {
        $fields = parent::getCMSFields();

        $fields->removeByName(['Theme', 'Height', 'HorizontalAlign', 'VerticalAlign', 'Padding', 'OverlayOpacity', 'Links']);

        $fields->addFieldsToTab('Root.Main', [
            TextField::create('Title', 'Headline'),
            TextareaField::create('Subtitle', 'Sub Headline'),
            TextareaField::create('Content', 'Optional blurb')->setRows(3),
            UploadField::create('BackgroundImage', 'Background image')
                ->setFolderName('uploads/elements/hero slides')
                ->setAllowedFileCategories('image/supported'),
                
            FieldGroup::create(
            'Appearance', // group title (must be first arg)
            DropdownField::create('Theme', 'Theme', [
                'light' => 'Light',
                'dark'  => 'Dark',
            ]),
            DropdownField::create('Height', 'Height', [
                'auto'   => 'Auto',
                'short'  => 'Short',
                'medium' => 'Medium',
                'tall'   => 'Tall',
                'full'   => 'Full viewport',
            ]),
            DropdownField::create('HorizontalAlign', 'Horizontal Alignment', [
                'center' => 'Center',
                'left'   => 'Left',
                'right'  => 'Right',
            ]),
            DropdownField::create('VerticalAlign', 'Vertical Alignment', [
                'top'    => 'Top',
                'middle' => 'Middle',
                'bottom' => 'Bottom',
            ]),
            DropdownField::create('Padding', 'Padding', [
                'none'   => 'None',
                '20px'   => '20px',
                '40px'   => '40px',
                '60px'   => '60px',
            ]),
            NumericField::create('OverlayOpacity', 'Overlay opacity (0–100)')
                ->setDescription('Typical: 0–70')
            )
            ->setName('AppearanceGroup')          // optional: stable name for CSS/JS
            ->addExtraClass('stack'),            // optional: vertical stacked layout

            MultiLinkField::create('Links', 'Button Links')
        ]);

        return $fields;
    }

    // Convenience for template
    public function OverlayOpacityCss(): string
    {
        $pct = max(0, min(100, (int) $this->OverlayOpacity));
        return (string) round($pct / 100, 2);
    }

    public function HorizontalAlignClass(): string
    {
        return match ($this->owner->HorizontalAlign) {
            'center' => 'align-center text-center',
            'left' => 'align-left text-left',
            'right' => 'align-right text-right',
            default => '',
        };
    }
    public function VerticalAlignClass(): string
    {
        return match ($this->owner->VerticalAlign) {
            'top' => 'align-self-top',     
            'middle' => 'align-self-middle',
            'bottom' => 'align-self-bottom',
            default => '',
        };
    }
    public function PaddingClass(): string
    {
        return match ($this->owner->Padding) {
            'none' => '',
            '20px' => 'p-20',
            '40px' => 'p-40',
            '60px' => 'p-60',
            default => 'p-20',
        };
    }
}
