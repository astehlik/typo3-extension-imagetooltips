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
	 * Attributes added to the tooltip container
	 *
	 * @var array
	 */
	protected $additionalContainerAttributes;

	/**
	 * Appearance data attribute configuration
	 *
	 * @var array
	 */
	protected $appearanceAttributes = array(
		'tx-imagetooltips-position-x' => array(
			'configurationKey' => 'positionX',
			'column' => 'tooltip_position_x',
			'type' => 'tooltipsPositionHorizontal',
			'defaultValue' => 'center',
		),
		'tx-imagetooltips-position-y' => array(
			'configurationKey' => 'positionY',
			'column' => 'tooltip_position_y',
			'type' => 'tooltipsPositionVertical',
			'defaultValue' => 'top',
		),
		'tx-imagetooltips-offset-x' => array(
			'configurationKey' => 'offsetX',
			'column' => 'tooltip_offset_x',
			'type' => 'int',
			'defaultValue' => 0,
		),
		'tx-imagetooltips-offset-y' => array(
			'configurationKey' => 'offsetY',
			'column' => 'tooltip_offset_y',
			'type' => 'int',
			'defaultValue' => 0,
		),
		'tx-imagetooltips-opacity' => array(
			'configurationKey' => 'opacity',
			'column' => 'tooltip_opacity',
			'type' => 'int',
			'defaultValue' => 100,
		),
	);

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
	 * Allowed tooltip positions for the different directions
	 *
	 * @var array
	 */
	protected $validTooltipPositions = array(
		'vertical' => array(
			'top',
			'center',
			'bottom'
		),
		'horizontal' => array(
			'left',
			'center',
			'right'
		),
	);

	/**
	 * Renders the tooltip container if one was found for the current image.
	 *
	 * Use this as as userFunc in a stdWrap property
	 *
	 * @param $content
	 * @param $conf
	 * @return string
	 */
	public function main($content, $conf) {

		$this->conf = $conf;
		$this->tsfe = $GLOBALS['TSFE'];
		$this->typo3Db = $GLOBALS['TYPO3_DB'];
		$this->additionalContainerAttributes = array();

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

		$this->generateAppearanceDataAttributes($tooltipResult);

		$containerAttributes = t3lib_div::implodeAttributes($this->additionalContainerAttributes, TRUE);
		if (strlen($containerAttributes)) {
			$containerAttributes = ' ' . $containerAttributes;
		}

		return $content . '<div class="tx-imagetooltips-tooltip"' . $containerAttributes . '>' . $tooltipResult['tooltip_text'] . '</div>';
	}

	/**
	 * Runs through all configured appearance attributes and generates
	 * the matching data tag string
	 *
	 * @param array $tooltipResult
	 * @return void
	 */
	protected function generateAppearanceDataAttributes($tooltipResult) {

		if (!is_array($this->conf['appearance.'])) {
			return;
		}

		$appearanceConfig = $this->conf['appearance.'];

		foreach ($this->appearanceAttributes as $dataAttributeName => $appearanceAttributeConfig) {

			$configurationKey = $appearanceAttributeConfig['configurationKey'];
			$column = $appearanceAttributeConfig['column'];

			$value = isset($appearanceConfig[$configurationKey]) ? $appearanceConfig[$configurationKey] : '';
			$value = isset($tooltipResult[$column]) && strlen(trim($tooltipResult[$column])) ? $tooltipResult[$column] : $value;
			$value = trim($value);

			if (!strlen($value)) {
				continue;
			}

			switch ($appearanceAttributeConfig['type']) {
				case 'tooltipsPositionHorizontal':
				case 'tooltipsPositionVertical':
					$value = $this->getValidTooltipPosition($appearanceAttributeConfig['type'], $value);
					if ($value === FALSE) {
						continue;
					}
					break;
				case 'int':
					$value = intval($value);
					break;
			}

			if ($value == $appearanceAttributeConfig['defaultValue']) {
				continue;
			}

			// make sure opacity value is valid
			if ($dataAttributeName == 'tx-imagetooltips-opacity') {
				$value = t3lib_div::intInRange($value, 0, 100);
			}

			$this->additionalContainerAttributes['data-' . $dataAttributeName] = $value;
		}
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

		$this->additionalContainerAttributes['data-tx-imagetooltips-related-content-element'] = $currentContentUid;
		$this->additionalContainerAttributes['data-tx-imagetooltips-related-image-position'] = $currentImagePosition;

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
	 * Checks if the given $value is a valid value for the given tooltip $type.
	 *
	 * @param string $type either "tooltipsPositionHorizontal" or "tooltipsPositionVertical"
	 * @param string $value the value that should be checked
	 * @return mixed FALSE if invalid, otherwise $value
	 */
	protected function getValidTooltipPosition($type, $value) {

		$tooltipPosition = FALSE;
		$type = $type == 'tooltipsPositionHorizontal' ? 'horizontal' : 'vertical';

		if (in_array($value, $this->validTooltipPositions[$type])) {
			$tooltipPosition = $value;
		}

		return $tooltipPosition;
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