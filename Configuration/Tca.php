<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

$TCA['tx_imagetooltips_tooltip'] = array(
	'ctrl' => $TCA['tx_imagetooltips_tooltip']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'hidden,description,related_content_element,related_image_position,tooltip_text'
	),
	'columns' => array(

		'hidden' => array(
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.hidden',
			'config' => array(
				'type' => 'check',
				'default' => 0
			)
		),

		'description' => array(
			'label' => 'LLL:EXT:imagetooltips/Resources/Private/Language/locallang_db.xml:tx_imagetooltips_tooltip.description',
			'config' => array(
				'type' => 'input',
				'size' => 40,
				'max' => 256,
				'eval' => 'required',
			)
		),

		'related_content_element' => array(
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:imagetooltips/Resources/Private/Language/locallang_db.xml:tx_imagetooltips_tooltip.related_content_element',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'tt_content',
				'size' => 1,
				'minitems' => 1,
				'maxitems' => 1,
				'wizards' => array(
					'suggest' => array(
						'type' => 'suggest',
					),
				),
			),
		),

		'related_image_position' => array(
			'label' => 'LLL:EXT:imagetooltips/Resources/Private/Language/locallang_db.xml:tx_imagetooltips_tooltip.related_image_position',
			'config' => array(
				'type' => 'input',
				'eval' => 'int',
				'size' => 10,
			)
		),

		'tooltip_text' => array(
			'label' => 'LLL:EXT:imagetooltips/Resources/Private/Language/locallang_db.xml:tx_imagetooltips_tooltip.tooltip_text',
			'config' => array(
				'type' => 'text',
			),
			'defaultExtras' => 'richtext[]',
		),
	),
	'types' => array (
		'0' => array(
			'showitem' => 'hidden,description,related_content_element,related_image_position,tooltip_text',
		),
	),
);

?>