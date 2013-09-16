<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

t3lib_extMgm::addPItoST43('imagetooltips', 'Classes/TooltipPlugin.php', '_TooltipPlugin', 'list_type', 0);

?>