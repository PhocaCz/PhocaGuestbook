<?php
/**
 * @package    phocaguestbook
 * @subpackage Helpers
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('_JEXEC') or die;

/**
 * Phoca Guestbook Category Tree
 *
 * @package     phocaguestbook
 */
class PhocaguestbookCategories extends JCategories
{
	public function __construct($options = array())
	{
		$options['table'] = '#__phocaguestbook_items';
		$options['extension'] = 'com_phocaguestbook';
		parent::__construct($options);
	}
}
