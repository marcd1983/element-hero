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
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;

class ElementHero extends BaseElement
{
    private static array $db = [
        'Title'          => 'Varchar(255)',     // Headline
        'Content'        => 'HTMLText',
        'Theme'          => 'Enum("light,dark","dark")',
        'OverlayOpacity' => 'Int',
        'Height'         => 'Enum("auto,short,medium,tall,full","tall")',
        'VerticalAlign'  => 'Enum("top,middle,bottom","middle")',
        'HorizontalAlign'=> 'Enum("left,center,right","left")',
        'Padding'        => 'Enum("none,20px,40px,60px","none")',
    ];

    private static array $has_one = [
        'BackgroundImage' => Image::class,
    ];

    private static array $has_many = [
        'Links' => Link::class . '.Owner',
    ];

    private static array $defaults = [
        'VerticalAlign'   => 'middle',
        'HorizontalAlign' => 'left',
        'Padding'         => '20px',
        'Theme'           => 'dark',
        'Height'          => 'tall',
    ];

    private static array $owns = [
        'BackgroundImage',
        'Links',
    ];

    private static string $icon       = 'font-icon-block-banner';
    private static string $table_name = 'ElementHero';

    public function getType(): string
    {
        return 'Hero';
    }

    public function getCMSFields(): FieldList
    {
        $fields = parent::getCMSFields();

        $fields->removeByName([
            'Theme',
            'Height',
            'VerticalAlign',
            'HorizontalAlign',
            'Padding',
            'OverlayOpacity',
            'Links',
        ]);

        $fields->addFieldsToTab('Root.Main', [
            TextField::create('Title', 'Headline'),
            HTMLEditorField::create('Content', 'Hero Content'),
            UploadField::create('BackgroundImage', 'Background image')
                ->setFolderName('uploads/elements/hero-slides')
                ->setAllowedFileCategories('image/supported'),

            FieldGroup::create(
                'Appearance',
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
                    'left'   => 'Left',
                    'center' => 'Center',
                    'right'  => 'Right',
                ]),
                DropdownField::create('VerticalAlign', 'Vertical Alignment', [
                    'top'    => 'Top',
                    'middle' => 'Middle',
                    'bottom' => 'Bottom',
                ]),
                DropdownField::create('Padding', 'Padding', [
                    'none' => 'None',
                    '20px' => '20px',
                    '40px' => '40px',
                    '60px' => '60px',
                ]),
                NumericField::create('OverlayOpacity', 'Overlay opacity (0–100)')
                    ->setDescription('Typical: 0–70')
            )
                ->setName('AppearanceGroup')
                ->addExtraClass('stack'),

            MultiLinkField::create('Links', 'Button Links'),
        ]);

        return $fields;
    }

    // 0–100 int -> 0–1 float string
    public function OverlayOpacityCss(): string
    {
        $pct = max(0, min(100, (int) $this->OverlayOpacity));
        return (string) round($pct / 100, 2);
    }

    public function HorizontalAlignClass(): string
    {
        return match ($this->HorizontalAlign) {
            'center' => 'align-center text-center',
            'right'  => 'align-right text-right',
            'left'   => 'align-left text-left',
            default  => '',
        };
    }

    public function VerticalAlignClass(): string
    {
        return match ($this->VerticalAlign) {
            'top'    => 'align-self-top',
            'middle' => 'align-self-middle',
            'bottom' => 'align-self-bottom',
            default  => '',
        };
    }

    public function PaddingClass(): string
    {
        return match ($this->Padding) {
            'none' => '',
            '20px' => 'p-20',
            '40px' => 'p-40',
            '60px' => 'p-60',
            default => 'p-20',
        };
    }
}
