<?php
/**
 * @package    phocaguestbook
 * @subpackage Tables
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

//-- No direct access
defined('_JEXEC') || die('=;)');


/**
 * phocaguestbook Table class.
 *
 * @package    phocaguestbook
 * @subpackage Components
 */
class TablePhocaguestbook extends JTableNested 
{
	function __construct(& $db) {
		parent::__construct('#__phocaguestbook_items', 'id', $db);
	}
	
	function check() {
		if(empty($this->alias)) {
			$this->alias = JApplication::stringURLSafe($this->title);
		}
		if(empty($this->date)) {
			$this->date = JFactory::getDate()->toSql();
		}
		
		//$this->alias = PhocaGuestbookHelper::getAliasName($this->alias);
		return true;
	}	
	
}
