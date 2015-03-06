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
class PhocaguestbookModelPhocaguestbooks extends JModelList
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
				'title', 'a.title',
				'alias', 'a.alias',
				'date', 'a.date',
				'checked_out', 'a.checked_out',
				'checked_out_time', 'a.checked_out_time',
				'content', 'a.content',
				'username', 'a.username',
				'ip', 'a.ip',
				'catid', 'a.catid', 'category_title',
				'guestbook_title', 'guestbook_title',
				 
				'ordering', 'ordering',
				'level', 'a.level',
				'language', 'a.language',
				
				'published','a.published'
				
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
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$categoryId = $this->getUserStateFromRequest($this->context.'.filter.category_id', 'filter_category_id', null, 'int');
		$this->setState('filter.category_id', $categoryId);

		$state = $this->getUserStateFromRequest($this->context.'.filter.state', 'filter_published', '', 'string');
		$this->setState('filter.state', $state);
		
		$language = $this->getUserStateFromRequest($this->context.'.filter.language', 'filter_language', '');
		$this->setState('filter.language', $language);

		$level = $this->getUserStateFromRequest($this->context.'.filter.level', 'filter_level', 0, 'int');
		$this->setState('filter.level', $level);
		
		// Load the parameters.
		$params = JComponentHelper::getParams('com_phocaguestbook');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('ordering', 'desc');
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
		$id	.= ':'.$this->getState('filter.search');
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
		$query->from('`#__phocaguestbook_items` AS a');
        
        // Join over the language
		$query->select('l.title AS language_title');
		$query->join('LEFT', '`#__languages` AS l ON l.lang_code = a.language');

		// Join over the users for the checked out user.
		$query->select('uc.name AS editor');
		$query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

		// Join over the asset groups.
		//$query->select('ag.title AS access_level');
		//$query->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');

		// Join over the categories.
		$query->select('c.title AS category_title, c.title AS guestbook_title, c.id AS guestbook_id');
		$query->join('LEFT', '#__categories AS c ON c.id = a.catid');
		
		// No author for the guestbook
		
		//not root
		$query->where('a.id > 1');

		// Filter by published state.
		$published = $this->getState('filter.state');
		if (is_numeric($published)) {
			$query->where('a.published = '.(int) $published);
		}
		else if ($published === '') {
			$query->where('(a.published IN (0, 1))');
		}

		// Filter by a single or group of categories.
		$baselevel = 1;
		$categoryId = $this->getState('filter.category_id');
		if (is_numeric($categoryId) && (int)$categoryId > 0) {
			$cat_tbl = JTable::getInstance('Category', 'JTable');
			$cat_tbl->load($categoryId);
			$rgt = $cat_tbl->rgt;
			$lft = $cat_tbl->lft;
			$baselevel = (int) $cat_tbl->level;
			$query->where('c.lft >= '.(int) $lft);
			$query->where('c.rgt <= '.(int) $rgt);
		}
		elseif (is_array($categoryId)) {
			JArrayHelper::toInteger($categoryId);
			$categoryId = implode(',', $categoryId);
			$query->where('a.catid IN ('.$categoryId.')');
		}
		
		// Filter on the level.
		if ($level = $this->getState('filter.level')) {
			$query->where('a.level <= '.((int) $level));
		}
	
		// Filter by search in title.
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			}
			else {
				$search = $db->Quote('%'.$db->escape($search, true).'%');
				$query->where('(a.title LIKE '.$search.' OR a.content LIKE '.$search.' OR a.alias LIKE '.$search.')');
			}
		}
		
		// Filter on the language.
		if ($language = $this->getState('filter.language')) {
			$query->where('a.language = '.$db->quote($language));
		}
		
		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering', 'ordering');
		$orderDirn	= $this->state->get('list.direction', 'desc');
		if ($orderCol == 'category_title') {
			$orderCol = 'c.title '.$orderDirn.', a.lft '.$orderDirn;
		}
		if ($orderCol == 'ordering'){
			if ($orderDirn == 'DESC' || $orderDirn == 'desc') {
				$orderCol = 'a.rgt';
			} else {
				$orderCol = 'a.lft';
				$orderDirn = 'asc';
			}
		}
			//sqlsrv change
		if($orderCol == 'language')
			$orderCol = 'l.title';
		$query->order($db->escape($orderCol.' '.$orderDirn));

		//echo nl2br(str_replace('#__','dgv3n_',$query));
        return $query;
    }
}
