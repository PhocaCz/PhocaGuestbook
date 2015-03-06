<?php
/**
 * @package    phocaguestbook
 * @subpackage Models
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
//-- No direct access
defined('_JEXEC') || die('=;)');


//-- Import the class JModelList
jimport('joomla.application.component.modellist');


/**
 * phocaguestbook Model.
 *
 * @package phocaguestbook
 * @subpackage Models
 */
class PhocaguestbookModelPhocaguestbooklogs extends JModelList
{
	
	/**
	 * Constructor.
	 * @param	array	An optional associative array of configuration settings.
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'a.id',
				'date', 'a.date',
				'ip', 'a.ip',
				'postid','a.postid',
				'captchaid','a.captchaid',
				'used_time','a.used_time',
				'incoming_page','a.incoming_page',  
				'catid', 'a.catid', 'category_title',
				'guestbook_title', 'guestbook_title',
				'state','a.state',
				'fields','a.fields',
				'hidden_field','a.hidden_field',
				'forbidden_word','a.forbidden_word',
				'session','a.session',
				'ip_list','a.ip_list',
				'ip_stopforum','a.ip_stopforum',
				'ip_honeypot','a.ip_honeypot',
				'ip_botscout','a.ip_botscout',
				'content_akismet','a.content_akismet',
				'content_mollom','a.content_mollom'
			);
		}

		parent::__construct($config);
	}
	
	/**
	 * Method to auto-populate the model state.
	 * Note. Calling getState in this method will result in recursion.
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication();

		// Adjust the context to support modal layouts.
		if ($layout = $app->input->get('layout'))
		{
			$this->context .= '.'.$layout;
		}

		// Load the filter state.
		$categoryId = $this->getUserStateFromRequest($this->context.'.filter.category_id', 'filter_category_id', null, 'int');
		$this->setState('filter.category_id', $categoryId);

		$state = $this->getUserStateFromRequest($this->context.'.filter.state', 'filter_published', '', 'string');
		$this->setState('filter.state', $state);
		
		// Load the parameters.
		$params = JComponentHelper::getParams('com_phocaguestbook');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('id', 'desc');
	}
	
	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.state');
		$id	.= ':'.$this->getState('filter.item_id');
		$id	.= ':'.$this->getState('filter.category_id');

		return parent::getStoreId($id);
	}
	
    /**
     * Method to build an SQL query to load the list data.
     * Funktion um einen SQL Query zu erstellen der die Daten für die Liste läd.
     *
     * @return string SQL query
     */
    protected function getListQuery()
    {
        // Create a new query object.
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.*'
			)
		);
		$query->from('`#__phocaguestbook_logging` AS a');
        
		// Join over the categories.
		$query->select('c.title AS category_title, c.title AS guestbook_title, c.id AS guestbook_id');
		$query->join('LEFT', '#__categories AS c ON c.id = a.catid');
		
		// Join over the categories.
		$query->select('p.title, p.username, p.id AS post_id');
		$query->join('LEFT', '#__phocaguestbook_items AS p ON p.id = a.postid');
		
		// Filter by published state.
		$published = $this->getState('filter.state');
		if (is_numeric($published)) {
			$query->where('a.state = '.(int) $published);
		}

		// Filter by a single or group of categories.
		$categoryId = $this->getState('filter.category_id');
		if (is_numeric($categoryId) && (int)$categoryId > 0) {
			$cat_tbl = JTable::getInstance('Category', 'JTable');
			$cat_tbl->load($categoryId);
			$rgt = $cat_tbl->rgt;
			$lft = $cat_tbl->lft;
			$query->where('c.lft >= '.(int) $lft);
			$query->where('c.rgt <= '.(int) $rgt);
		}
		elseif (is_array($categoryId)) {
			JArrayHelper::toInteger($categoryId);
			$categoryId = implode(',', $categoryId);
			$query->where('a.catid IN ('.$categoryId.')');
		}
		
		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering', 'ordering');
		$orderDirn	= $this->state->get('list.direction', 'desc');
		if ($orderCol == 'category_title') {
			$orderCol = 'c.title '.$orderDirn;
		}
		
		$query->order($db->escape($orderCol.' '.$orderDirn));

		//echo nl2br(str_replace('#__','dgv3n_',$query)); die();
        return $query;
    }
}
