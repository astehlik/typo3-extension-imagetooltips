<?php
/*                                                                        *
 * This script belongs to the TYPO3 extension "imagetooltips".            *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License as published by the Free   *
 * Software Foundation, either version 3 of the License, or (at your      *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General      *
 * Public License for more details.                                       *
 *                                                                        *
 * You should have received a copy of the GNU General Public License      *
 * along with the script.                                                 *
 * If not, see http://www.gnu.org/licenses/gpl.html                       *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * Plugin that renders the tooltips
 */
class tx_imagetooltips_TooltipPlugin extends tslib_pibase {

	/**
	 * Should be same as classname of the plugin, used for CSS classes, variables
	 *
	 * @var string
	 */
	var $prefixId = 'tx_imagetooltips_TooltipPlugin';

	/**
	 * Path to the plugin class script relative to extension directory, eg. 'pi1/class.tx_newfaq_pi1.php'
	 *
	 * @var string
	 */
	var $scriptRelPath = 'Classes/TooltipPlugin.php';

	/**
	 * Contains cache for tooltips to reduce database queries
	 *
	 * @var array
	 */
	protected static $tooltipCache = array();

	/**
	 * Extension key.
	 *
	 * @var string
	 */
	var $extKey = 'imagetooltips';

	/**
	 * TYPO3 Frontent
	 *
	 * @var tslib_fe
	 */
	protected $tsfe;

	/**
	 * TYPO3 database
	 *
	 * @var t3lib_db
	 */
	protected $typo3Db;

	/**
	 * Renders the tooltip container if one was found for the current image.
	 *
	 * Use this as as userFunc in a stdWrap property
	 *
	 * @param $content
	 * @param $conf
	 * @return string
	 */
	function main($content, $conf) {

		$this->conf = $conf;
		$this->tsfe = $GLOBALS['TSFE'];
		$this->typo3Db = $GLOBALS['TYPO3_DB'];

		$tooltipResult = $this->getTooltipsForCurrentImage();

		if ($tooltipResult === NULL) {
			return $content;
		}

		$enableT3jquery = $conf['t3jquery.']['enable'];
		if (isset($conf['t3jquery.']['enable.'])) {
			$enableT3jquery = $this->cObj->stdWrap($enableT3jquery, $conf['t3jquery.']['enable.']);
		}

		if ($enableT3jquery && t3lib_extMgm::isLoaded('t3jquery')) {

			require_once(t3lib_extMgm::extPath('t3jquery') . 'class.tx_t3jquery.php');

			if (T3JQUERY === TRUE) {
				tx_t3jquery::addJs('', $conf['t3jquery.']['config.']);
			}
		}

		return $content . '<div class="tx-imagetooltips-tooltip">' . $tooltipResult['tooltip_text'] . '</div>';
	}

	/**
	 * Returns an array with the tooltip data or NULL if no
	 * tooltips exist for the current image
	 *
	 * @return mixed
	 */
	protected function getTooltipsForCurrentImage() {

		$currentContentUid = intval($this->cObj->data['uid']);
		$currentImagePosition = intval($this->tsfe->register['IMAGE_NUM_CURRENT']);

		if (!array_key_exists($currentContentUid, self::$tooltipCache)) {
			$this->loadTooltipsFromDatabase($currentContentUid);
		}

		$tooltips = self::$tooltipCache[$currentContentUid];

		if (!array_key_exists($currentImagePosition, $tooltips)) {
			return NULL;
		} else {
			return $tooltips[$currentImagePosition];
		}
	}

	/**
	 * Loads the tooltips for the given content UID from the database
	 * into the tooltip cache array
	 *
	 * @param int $contentUid
	 */
	protected function loadTooltipsFromDatabase($contentUid) {

		$tooltipsPid = intval($this->conf['tooltipsPid']);

		// search in current page and in tooltips page, if defined
		$tooltipEnableFieldQuery = ' AND (tx_imagetooltips_tooltip.pid=' . $this->tsfe->id;
		$tooltipEnableFieldQuery .= $tooltipsPid ? ' OR tx_imagetooltips_tooltip.pid=' . $tooltipsPid . ')' : ')';
		$tooltipEnableFieldQuery .= $this->cObj->enableFields('tx_imagetooltips_tooltip');

		$tooltipWhere = 'related_content_element=' . $contentUid;
		$tooltipWhere .= $tooltipEnableFieldQuery;
		$tooltipResult = $this->typo3Db->exec_SELECTquery('*', 'tx_imagetooltips_tooltip', $tooltipWhere);

		$tooltipArray = array();
		while ($tooltipRow = $this->typo3Db->sql_fetch_assoc($tooltipResult)) {
			$tooltipArray[$tooltipRow['related_image_position']] = $tooltipRow;
		}

		self::$tooltipCache[$contentUid] = $tooltipArray;
	}

}

?>