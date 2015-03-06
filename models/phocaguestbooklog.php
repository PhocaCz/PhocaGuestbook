<?php
/**
 * @package    phocaguestbook
 * @subpackage Models
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
//-- No direct access
defined('_JEXEC') || die('=;)');

/**
 * phocaguestbook Model.
 *
 * @package    phocaguestbook
 * @subpackage Models
 */
class PhocaguestbookModelPhocaguestbookLog extends JModelAdmin
{
	
    /**
     * Returns a reference to the a Table object, always creating it.
     * @internal param \The $type table type to instantiate
     */
    public function getTable($type = 'phocaguestbookLogging', $prefix = 'Table', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    /**
     * Method to get the record form.
     *
     * @param array $data Data for the form.
     * @param boolean $loadData True if the form is to load its own data (default case), false if not.
     *
     * @return mixed A JForm object on success, false on failure
     */
    public function getForm($data = array(), $loadData = true)
    {
		return false;
    }
	
}
