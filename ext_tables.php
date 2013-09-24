<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript/', 'Image tooltip rendering');
t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript/4.5/', 'Image tooltip rendering 4.5 & 4.6');
t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript/Example/', 'Image tooltip example integration');


$TCA['tx_imagetooltips_tooltip'] = array(
	'ctrl' => array(
		'title' => 'LLL:EXT:imagetooltips/Resources/Private/Language/locallang_db.xml:tx_imagetooltips_tooltip',
		'label' => 'description',
		'default_sortby' => 'ORDER BY description DESC',
		'crdate' => 'crdate',
		'tstamp' => 'tstamp',
		'delete' => 'deleted',
		'cruser_id' => 'cruser_id',
		'enablecolumns' => array(
			'disabled' => 'hidden',
		),
		'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY) . 'ext_icon.gif',
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/Tca.php',
		'dividers2tabs' => 1
	)
);

t3lib_extMgm::allowTableOnStandardPages('tx_imagetooltips_tooltip');

?>