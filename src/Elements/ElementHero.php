<?php

namespace Antlion\ElementHero\Elements;

use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\NumericField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\ToggleCompositeField;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\LinkField\Form\MultiLinkField;
use SilverStripe\LinkField\Models\Link;
use TractorCow\Colorpicker\Forms\ColorField;

class ElementHero extends BaseElement
{
    private static array $db = [
        'Title'           => 'Varchar(255)',
        'Content'         => 'HTMLText',
        'Theme'           => 'Enum("light,dark","dark")',
        'Height'          => 'Enum("auto,short,medium,tall,full","tall")',
        'VerticalAlign'   => 'Enum("top,middle,bottom","middle")',
        'HorizontalAlign' => 'Enum("left,center,right","left")',
        'Padding'         => 'Enum("none,small,medium,large","small")',
        'BackgroundColor' => 'Varchar(20)',
        'OverlayColor'    => 'Varchar(20)',
        'OverlayOpacity'  => 'Int', // 0–100
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
        'Padding'         => 'small',
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

    public function inlineEditable(): bool
    {
        return false;
    }

    public function getCMSFields(): FieldList
    {
        $fields = parent::getCMSFields();

        $fields->removeByName([
            'Title',
            'Content',
            'Theme',
            'Height',
            'VerticalAlign',
            'HorizontalAlign',
            'Padding',
            'BackgroundColor',
            'BackgroundImage',
            'OverlayColor',
            'OverlayOpacity',
            'Links',
        ]);

        $fields->addFieldsToTab('Root.Main', [
            TextField::create('Title', 'Headline'),
            HTMLEditorField::create('Content', 'Hero Content'),

            ToggleCompositeField::create(
                'HeroAppearance',
                'Appearance',
                [
                    DropdownField::create('Theme', 'Theme', [
                        'light' => 'Light',
                        'dark'  => 'Dark',
                    ]),

                    HeaderField::create('HeroLayoutHeading', 'Layout', 3),
                    DropdownField::create('Height', 'Height', [
                        'auto'   => 'Auto',
                        'short'  => 'Short',
                        'medium' => 'Medium',
                        'tall'   => 'Tall',
                        'full'   => 'Full viewport',
                    ]),
                    DropdownField::create('HorizontalAlign', 'Horizontal alignment', [
                        'left'   => 'Left',
                        'center' => 'Center',
                        'right'  => 'Right',
                    ]),
                    DropdownField::create('VerticalAlign', 'Vertical alignment', [
                        'top'    => 'Top',
                        'middle' => 'Middle',
                        'bottom' => 'Bottom',
                    ]),
                    DropdownField::create('Padding', 'Content padding', [
                        'none'   => 'None',
                        'small'  => 'Small',
                        'medium' => 'Medium',
                        'large'  => 'Large',
                    ]),

                    HeaderField::create('HeroBackgroundHeading', 'Background', 3),
                    UploadField::create('BackgroundImage', 'Background image')
                        ->setFolderName('uploads/elements/hero-slides')
                        ->setAllowedFileCategories('image/supported'),
                    ColorField::create('BackgroundColor', 'Background color')
                        ->setDescription('Solid color fallback when no image is set'),

                    HeaderField::create('HeroOverlayHeading', 'Overlay', 3),
                    ColorField::create('OverlayColor', 'Overlay color'),
                    NumericField::create('OverlayOpacity', 'Overlay opacity (0–100)')
                        ->setDescription('e.g. 35 for 35% opacity'),
                ]
            ),

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
            'small'  => 'p-20',
            'medium' => 'p-40',
            'large'  => 'p-60',
            default  => '',
        };
    }

    public function HeroStyles(): string
    {
        $parts = [];
        if ($this->BackgroundColor) {
            $parts[] = 'background-color:#' . ltrim((string)$this->BackgroundColor, '#');
        }
        if ($this->BackgroundImage()->exists()) {
            $parts[] = "background-image:url('" . $this->BackgroundImage()->URL . "')";
        }
        if ($this->OverlayColor) {
            $parts[] = '--hero-overlay-bg:#' . ltrim((string)$this->OverlayColor, '#');
        }
        return implode(';', $parts);
    }
}
