<?php
/**
 * @package    phocaguestbook
 * @subpackage Tables
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

//-- No direct access
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Table\Table;
use Joomla\CMS\Factory;


/**
 * phocaguestbook Table class.
 *
 * @package    phocaguestbook
 * @subpackage Components
 */
class TablePhocaguestbookLogging extends Table
{
	function __construct(& $db) {
		parent::__construct('#__phocaguestbook_logging', 'id', $db);
	}
	
	function check() {
		if(empty($this->date)) {
			$this->date = Factory::getDate()->toSql();
		}
		
		return true;
	}	
	
}
