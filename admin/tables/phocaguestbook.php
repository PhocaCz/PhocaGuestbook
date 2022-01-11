<?php
/**
 * @package    phocaguestbook
 * @subpackage Tables
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

//-- No direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Application\ApplicationHelper;
use Joomla\CMS\Table\Nested;
use Joomla\CMS\Factory;


/**
 * phocaguestbook Table class.
 *
 * @package    phocaguestbook
 * @subpackage Components
 */
class TablePhocaguestbook extends Nested
{
	function __construct(& $db) {
		parent::__construct('#__phocaguestbook_items', 'id', $db);
	}

	function check() {
		if(empty($this->alias)) {
			$this->alias = ApplicationHelper::stringURLSafe($this->title);
		}
		if(empty($this->date)) {
			$this->date = Factory::getDate()->toSql();
		}

		//$this->alias = PhocaGuestbookHelper::getAliasName($this->alias);
		return true;
	}

}
