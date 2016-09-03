<?php
/**
 * Description
 *
 * @package silverstripe
 * @subpackage mysite
 */
class PreviewsBlock extends BlockObject
{
    /**
     * Singular name for CMS
     * @var string
     */
    private static $singular_name = 'Previews/Teasers';

    /**
     * Many_many relationship
     * @var array
     */
    private static $many_many = array(
        'CustomPreviews' => 'PlaceablePreviewLink',
    );

    /**
     * {@inheritdoc }
     * @var array
     */
    private static $many_many_extraFields = array(
        'CustomPreviews' => array(
            'Sort' => 'Int'
        )
    );

    /**
     * CMS Page Fields
     * @return FieldList
     */
    public function getCMSPageFields()
    {
        $fields = parent::getCMSPageFields();
        if ($this->Preset()->PreviewType == 'custom') {
            $fields->addFields(
                array(
                    GridField::create(
                        'CustomPreviews',
                        _t('PreviewsBlock.PREVIEWS', 'Preview(s)'),
                        $this->CustomPreviews(),
                        GridFieldConfig_RelationEditor::create()
                            ->addComponent(new GridFieldOrderableRows())
                    )
                )
            );
        }
        $this->extend('updateCMSPageFields', $fields);
        return $fields;
    }

    /**
     * Returns a list of preview links
     * @return arraylist
     */
    public function Previews()
    {
        switch ($this->Preset()->PreviewType) {
            case 'custom':
                $links = $this->CustomPreviews()->sort('Sort ASC');
                break;
            case 'specifiedchildren':
                $links = $this->Preset()->ParentPage()->Children();
                break;
            case 'currentchildren':
            default:
                $links = Director::get_current_page()->Children();
                break;
        }
        return $links->Limit($this->Preset()->LinkLimit);
    }
}
class PreviewsBlock_Controller extends BlockObject_Controller
{
    public function init() {
        parent::init();
    }
}
class PreviewsBlock_Preset extends BlockObject_Preset
{
    /**
     * Database fields
     * @var array
     */
    private static $db = array(
        'PreviewType' => 'Enum("currentchildren,custom,specifiedchildren", "currentchildren")',
        'PreviewLimit' => 'Int'
    );

    /**
     * CMS Fields
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->addFieldsToTab(
            'Root.Main',
            array(
                DropdownField::create(
                    'PreviewType',
                    'Type',
                    array(
                        'currentchildren' => _t('PreviewsBlock.CURRENTCHILDREN', 'List all sub pages of this page'),
                        'specifiedchildren' => _t('PreviewsBlock.SPECIFIEDCHILDREN', 'Specify a page and list all its sub pages'),
                        'custom' => _t('PreviewsBlock.CUSTOM', 'Specify each preview/teaser on the page'),
                    )
                ),
                DisplayLogicWrapper::create(
                    TreeDropdownField::create(
                        'ParentPageID',
                        _t('PreviewsBlock.SELECTAPAGE', 'Select a page'),
                        'SiteTree'
                    )
                )->displayIf("LinkType")->isEqualTo("children")->end(),
                NumericField::create(
                    'PreviewLimit',
                    _t('PreviewsBlock.LIMITPREVIEWS', 'Limit previews')
                )
                ->setDescription(_t('PreviewsBlock.LIMITPREVIEWSDESCRIPTION', '0 equals unlimited amount.'))
            )
        );
        return $fields;
    }
}
