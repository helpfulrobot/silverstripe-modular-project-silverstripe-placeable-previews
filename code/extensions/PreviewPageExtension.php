<?php
/**
 * Adds preview
 *
 * @package silverstripe
 * @subpackage mysite
 */
class PreviewPageExtension extends DataExtension
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
     * Update Fields
     * @return FieldList
     */
    public function updateCMSFields(FieldList $fields)
    {
        $fields->addFieldsToTab(
            'Root.Preview',
            array(
                UploadField::create('PreviewImage','Image'),
                TextareaField::create('PreviewSummary','Summary')
                    ->setRows(3),
                TextField::create('PreviewMore','More')
            )
        );
        return $fields;
    }
}
