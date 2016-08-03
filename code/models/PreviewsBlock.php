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
        'CustomPreviews' => 'PreviewLink',
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
        $fields->addFieldsToTab(
            'Root.Main',
            array(
                GridField::create(
                    'CustomPreviews',
                    _t('Placeable.PREVIEWS', 'Preview(s)')
                    $this->Previews(),
                    GridFieldConfig_RelationEditor::create()
                        ->addComponent(new GridFieldOrderableRows())
                )
            )
        );
        $this->extend('updateCMSPageFields', $fields);
        return $fields;
    }

    /**
     * Returns a list of preview links
     * @return arraylist
     */
    public function Previews()
    {
        $currentPage = Director::get_current_page();
        switch ($this->LinkType) {
            case 'custom':
                return $this->CustomPreviews()->sort('Sort ASC');
            case 'children':
                return $this
                    ->ParentPage()
                    ->Children()
                    ->Limit($this->LinkLimit)
                    ->Exclude(
                        array(
                            "ID" => $currentPage->ID
                        )
                    );
            case 'currentchildren':
            default:
                return $currentPage
                    ->Children()
                    ->Limit($this->LinkLimit);
        }
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
     * Singular name for CMS
     * @var string
     */
    private static $singular_name = 'Previews/Teasers Preset';

    /**
     * Database fields
     * @var array
     */
    private static $db = array(
        'PreviewType' => 'Enum("currentchildren,custom,children", "currentchildren")',
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
                        "currentchildren" => "List all sub pages of this page",
                        "children" => "Specify a page and list all its sub pages",
                        "custom" => "Specify each link"
                    )
                ),
                DisplayLogicWrapper::create(
                    TreeDropdownField::create(
                        'ParentPageID',
                        'Select a page',
                        'SiteTree'
                    )
                )->displayIf("LinkType")->isEqualTo("children")->end(),
                DisplayLogicWrapper::create(
                    NumericField::create(
                        'PreviewLimit',
                        'Preview links'
                    )
                    ->setDescription("0 equals unlimited amount.")
                )->displayIf("LinkType")->isNotEqualTo("custom")->end()
            )
        );
        return $fields;
    }
}