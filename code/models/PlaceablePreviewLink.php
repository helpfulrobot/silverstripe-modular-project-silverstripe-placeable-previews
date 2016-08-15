<?php
/**
 * Description
 *
 * @package silverstripe
 * @subpackage mysite
 */
class PlaceablePreviewLink extends Link
{
    /**
     * Database fields
     * @var array
     */
    private static $db = array(
        'PreviewSummary' => 'Text',
        'PreviewMore' => 'Text'
    );

    /**
     * Has_one relationship
     * @var array
     */
    private static $has_one = array(
        'PreviewImage' => 'Image'
    );

    /**
     * Define the default values for all the $db fields
     * @var array
     */
    private static $defaults = array(
        'PreviewMore' => 'Read more'
    );

    /**
     * Define extensions
     * @var array
     */
    private static $extensions = array(
        'Versioned("Stage","Live")'
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
                UploadField::create(
                    'PreviewImage',
                    _t('PlaceablePreviewLink.IMAGE', 'Image')
                ),
                TextareaField::create(
                    'PreviewSummary',
                    _t('PlaceablePreviewLink.SUMMARY', 'Summary')
                )->setRows(3),
                TextField::create(
                    'PreviewMore',
                    _t('PlaceablePreviewLink.MORE', 'More')
                )
            ),
            'Type'
        );
        return $fields;
    }
}
