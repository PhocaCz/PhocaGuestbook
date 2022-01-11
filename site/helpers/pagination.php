<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_JEXEC' ) or die( 'Restricted access' );
use Joomla\CMS\Pagination\Pagination;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

jimport('joomla.html.pagination');
class PhocaGuestbookPaginationPosts extends Pagination
{
	function getLimitBox() {
		$app		= Factory::getApplication();
		$paramsC 	= $app->getParams();
		$pagination 		= $paramsC->get( 'pagination_posts', '5,10,15,20' );
		$paginationArray	= explode( ',', $pagination );
		
		// Initialize variables
		$limits = array ();

		foreach ($paginationArray as $paginationValue) {
			$limits[] = HTMLHelper::_('select.option', $paginationValue);
		}
		$limits[] = HTMLHelper::_('select.option', '0', Text::_('COM_PHOCAGUESTBOOK_ALL'));

		$selected = $this->viewall ? 0 : $this->limit;

		// Build the select list
		if ($app->isClient('administrator')) {
			$html = HTMLHelper::_('select.genericlist',  $limits, 'limit', 'class="form-control input-mini" size="1" onchange="Joomla.submitform();"', 'value', 'text', $selected);
		} else {
			$html = HTMLHelper::_('select.genericlist',  $limits, 'limit', 'class="form-control input-mini" size="1" onchange="this.form.submit()"', 'value', 'text', $selected);
		}
		return $html;
	}
}
?>