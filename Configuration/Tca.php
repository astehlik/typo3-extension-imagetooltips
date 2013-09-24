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
				'size' => 10,
				// we use num instead of int because 0 will be displayed as empty string
				'eval' => 'num',
			)
		),

		'tooltip_text' => array(
			'label' => 'LLL:EXT:imagetooltips/Resources/Private/Language/locallang_db.xml:tx_imagetooltips_tooltip.tooltip_text',
			'config' => array(
				'type' => 'text',
			),
			'defaultExtras' => 'richtext[]',
		),

		'tooltip_position_y' => array(
			'label' => 'LLL:EXT:imagetooltips/Resources/Private/Language/locallang_db.xml:tx_imagetooltips_tooltip.tooltip_position_y',
			'exclude' => 1,
			'config' => array(
				'type' => 'select',
				'items' => array(
					array('LLL:EXT:imagetooltips/Resources/Private/Language/locallang_db.xml:tx_imagetooltips_tooltip.tooltip_position_y.I.default', ''),
					array('LLL:EXT:imagetooltips/Resources/Private/Language/locallang_db.xml:tx_imagetooltips_tooltip.tooltip_position_y.I.top', 'top'),
					array('LLL:EXT:imagetooltips/Resources/Private/Language/locallang_db.xml:tx_imagetooltips_tooltip.tooltip_position_y.I.center', 'center'),
					array('LLL:EXT:imagetooltips/Resources/Private/Language/locallang_db.xml:tx_imagetooltips_tooltip.tooltip_position_y.I.bottom', 'bottom'),
				),
				'minitems' => 0,
				'maxitems' => 1,
				'size' => 1,
			)
		),

		'tooltip_position_x' => array(
			'label' => 'LLL:EXT:imagetooltips/Resources/Private/Language/locallang_db.xml:tx_imagetooltips_tooltip.tooltip_position_x',
			'exclude' => 1,
			'config' => array(
				'type' => 'select',
				'items' => array(
					array('LLL:EXT:imagetooltips/Resources/Private/Language/locallang_db.xml:tx_imagetooltips_tooltip.tooltip_position_x.I.default', ''),
					array('LLL:EXT:imagetooltips/Resources/Private/Language/locallang_db.xml:tx_imagetooltips_tooltip.tooltip_position_x.I.right', 'right'),
					array('LLL:EXT:imagetooltips/Resources/Private/Language/locallang_db.xml:tx_imagetooltips_tooltip.tooltip_position_x.I.center', 'center'),
					array('LLL:EXT:imagetooltips/Resources/Private/Language/locallang_db.xml:tx_imagetooltips_tooltip.tooltip_position_x.I.left', 'left'),
				),
				'minitems' => 0,
				'maxitems' => 1,
				'size' => 1,
			)
		),

		'tooltip_offset_x' => array(
			'label' => 'LLL:EXT:imagetooltips/Resources/Private/Language/locallang_db.xml:tx_imagetooltips_tooltip.tooltip_offset_x',
			'exclude' => 1,
			'config' => array(
				'type' => 'input',
				'size' => 10,
				// we use alphanum_x instead of int because 0 will be displayed as
				// empty string and we want to allow negative values
				'eval' => 'alphanum_x',
			)
		),

		'tooltip_offset_y' => array(
			'label' => 'LLL:EXT:imagetooltips/Resources/Private/Language/locallang_db.xml:tx_imagetooltips_tooltip.tooltip_offset_y',
			'exclude' => 1,
			'config' => array(
				'type' => 'input',
				'size' => 10,
				// we use alphanum_x instead of int because 0 will be displayed as
				// empty string and we want to allow negative values
				'eval' => 'alphanum_x',
			)
		),

		'tooltip_opacity' => array(
			'label' => 'LLL:EXT:imagetooltips/Resources/Private/Language/locallang_db.xml:tx_imagetooltips_tooltip.tooltip_opacity',
			'exclude' => 1,
			'config' => array(
				'type' => 'input',
				'size' => 10,
				'min' => 0,
				'max' => '100',
				// we use num instead of int because 0 will be displayed as empty string
				'eval' => 'num',
			)
		),
	),
	'types' => array(
		0 => array(
			'showitem' =>
					'hidden,description,related_content_element,related_image_position,tooltip_text,
				--div--;LLL:EXT:imagetooltips/Resources/Private/Language/locallang_db.xml:tabs.appearance,tooltip_position_x,tooltip_position_y,tooltip_offset_x,tooltip_offset_y,tooltip_opacity',
		),
	),
);

?>