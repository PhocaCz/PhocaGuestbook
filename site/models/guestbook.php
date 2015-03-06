<?php
/**
 * @package    phocaguestbook
 * @subpackage Models
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

//-- No direct access
defined('_JEXEC') || die('=;)');
require_once JPATH_COMPONENT.'/helpers/phocaguestbookemail.php';


class PhocaguestbookModelGuestbook extends JModelForm
{
	//category/guestbook
	protected $_guestbook;
	protected $_data;
	protected $_guestbookId;
		
	function __construct() {
		$app 	= JFactory::getApplication('site');
		
		// Load ID
		$pk  = $app->input->getInt('cid');
		if (!$pk) {
			$pk  = $app->input->getInt('catid');
		}
		
		if (!$pk) {
			$this->_guestbookId =  -1; //dummy
		} else {
			$this->_guestbookId = $pk;
		}
		
		parent::__construct();
	}
		
		

	function store(&$data) {
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$uri 	= JFactory::getURI();
		$app    = JFactory::getApplication();
		$params = $this->getState('params');
		
		// cat id test
		$id = $this->getState('category.id');
		if (!$id){
			jexit(JText::_('COM_PHOCAGUESTBOOK_POSSIBLE_SPAM_DETECTED'));
		}
			
		// HTML Purifier - - - - - - - - - - 
		if ($params->get('enable_html_purifier') == 0) {
			$filterTags		= '';//preg_split( '#[,\s]+#', trim( ) ); // black list method is used
			$filterAttrs	= '';//preg_split( '#[,\s]+#', trim( ) ); // black list method is used
			$filter	= new JFilterInput( $filterTags, $filterAttrs, 1, 1, 1 );
			$data['guestbook_content']	= $filter->clean( $data['content'] );
		} else {		
			require_once( JPATH_COMPONENT.'/assets/library/HTMLPurifier.standalone.php' );
			$configP = HTMLPurifier_Config::createDefault();
			$configP->set('Core.Encoding', 'UTF-8');
			$configP->set('HTML.Doctype', 'XHTML 1.0 Transitional');
			$configP->set('HTML.TidyLevel', 'medium');
			$configP->set('HTML.Allowed','strong,em,p[style],span[style],img[src|width|height|alt|title],li,ul,ol,a[href],u,strike,br');
			$purifier = new HTMLPurifier($configP);
			$data['guestbook_content'] = $purifier->purify($data['content']);
		}
		// Maximum of character, they will be saved in database
		$data['guestbook_content']	= substr($data['guestbook_content'], 0, $params->get('max_char'));
		
		
		//review item?
		if ($data['published']) {
			$data['published'] = $params->get('review_item', 1);
		}
		$data['ip'] = $_SERVER["REMOTE_ADDR"];
		$data['date'] = gmdate('Y-m-d H:i:s');   // Create the timestamp for the date
			

		// SAVING DATA - - - - - - - - - - 
		// TRUE MODEL
		$row = $this->getTable('phocaguestbook');
		
		$this->checkReference();
		
	
	
		// Bind the form fields to the table
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
			
		// if new item, order last in appropriate group
		if (!isset($data['parent_id'])) {
			$data['parent_id'] = 0;
		}
		if (!$row->id) {
			$row->setLocation($data['parent_id'], 'last-child');
		}
		
		
	
		// Make sure the table is valid
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		
		// Store the Phoca guestbook table to the database
		if (!$row->store()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		// Everything OK - send email for menu item and guestbook
		if ($params->get('send_email') > 0) {
			PhocaguestbookEmailHelper::sendPhocaGuestbookMail($params->get('send_email'), $data, JFactory::getURI()->toString(), $params);
		}
		if ($params->get('send_super_email') > 0) {
			PhocaguestbookEmailHelper::sendPhocaGuestbookMail($params->get('send_super_email'), $data, JFactory::getURI()->toString(), $params);
		}
		
		$data['id'] = $row->id;
			
		return true;
	}
	
	
	
	function doLog(&$logging, $success) {
		$params = $this->getState('params');

		if($params->get('enable_logging') &&
		  ($params->get('logging_failed') || $success )) {
			$logging->date = gmdate('Y-m-d H:i:s');   // Create the timestamp for the date
			if (!$success) {
				$logging->state = 3;	//1 = published, 2 = review, 3 = reject
			}
		
			// Make sure the table is valid
			if (!$logging->check()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			
			// Store the Phoca guestbook table to the database
			if (!$logging->store()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}
		
		return true;
	}
	

	protected function populateState($ordering = null, $direction = null)
	{
		$app 	= JFactory::getApplication('site');
		$user	= JFactory::getUser();
				
		// Load filter, enable filter.published
		if ((!$user->authorise('core.edit.state', 'com_phocaguestbook')) &&  (!$user->authorise('core.edit', 'com_phocaguestbook'))){
			// limit to published for people who can't edit or edit.state.
			$this->setState('filter.published', 1);
		}
		else {
			$this->setState('filter.published', array(0, 1, 2));
		}

		$this->setState('category.id', $this->_guestbookId);
		//list.xy	//read from post value on get data
		
		// Set Language and Layout
		$this->setState('filter.language', $app->getLanguageFilter());
		$this->setState('layout', $app->input->get('layout'));
		
		// List state information.
		//parent::populateState('a.title', 'asc');
	}
	
	
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_phocaguestbook.guestbook', 'guestbook', array('control' => 'jform', 'load_data' => true));
		if (empty($form)) {
			return false;
		}

		$id = $this->getState('category.id');
		$params = $this->getState('params');
		
		if (empty($params) || empty($id) || $id==-1) {
			return false;
		}
		
		// Set required or not && disable if not available
		if(!$params->get('display_title_form', 0)){
			$form->removeField('title');
		} else if ($params->get('display_title_form') == 2){
			$form->setFieldAttribute('title', 'required', 'true');
		}

		if(!$params->get('display_name_form', 0)){
			$form->removeField('username');
		} else if ($params->get('display_name_form') == 2){
			$form->setFieldAttribute('username', 'required', 'true');
		}

		if(!$params->get('display_email_form', 0)){
			$form->removeField('email');
		} else if ($params->get('display_email_form') == 2){
			$form->setFieldAttribute('email', 'required', 'true');
		}

		if(!$params->get('display_website_form', 0)){
			$form->removeField('homesite');
		} else if ($params->get('display_website_form') == 2){
			$form->setFieldAttribute('homesite', 'required', 'true');
		}

		if(!$params->get('display_content_form', 0)){
			$form->removeField('content');
		} else if ($params->get('display_content_form') == 2){
			$form->setFieldAttribute('content', 'required', 'true');
		}
		
		if (!$params->get('enable_hidden_field', 0)){
			$form->removeField('hidden_field');
		} else {
			$form->setFieldAttribute('hidden_field', 'id', $params->get('hidden_field_id'));
			$form->setFieldAttribute('hidden_field', 'class', $params->get('hidden_field_class'));
			$form->setFieldAttribute('hidden_field', 'name', $params->get('hidden_field_name'));
		}
		
		if (!$params->get('enable_captcha')) {
			$form->removeField('captcha');
		} else {
			switch ($params->get('captcha_id')){
				case 8: case 7: case 6: case 5: case 4: case 3: case 2:
					$form->setFieldAttribute('captcha', 'type', 'phocacaptcha');
					$form->setFieldAttribute('captcha', 'captcha_id', $params->get('captcha_id'));
					$form->setFieldAttribute('captcha', 'validate', 'phocaguestbookcaptcha');
					break;
				case 1: default: 
					break;	//do nothing, type = captcha
			}
		}
		
		//$form->setFieldAttribute('content', 'type', 'textarea');
		if (false == $params->get('enable_editor')) {
			$form->setFieldAttribute('content', 'type', 'textarea');	
		}

		
		$form->setValue('version', null, $params->get('form_style'));

		return $form;
	}
	
	protected function loadFormData()
	{
		$data = (array) JFactory::getApplication()->getUserState('com_phocaguestbook.guestbook.data', array());
		return $data;
	}
	
	
	
    /**
     * Gets the Data.
     *
     * @return string The greeting to be displayed to the user
     */
    public function getData()
    {
		// Get pagination request variables
		$params = $this->getState('params');

		$start  = JRequest::getInt('start', $this->getState('list.start', 0));
		$limit  = JRequest::getInt('limit', $this->getState('list.limit', $params->get('default_pagination')));
		$this->setState('list.start', $start);
		$this->setState('list.limit', $limit);
	
		$order  = $params->get('items_order', 'ordering');	
		$dir    = $params->get('items_orderdir', 'ASC');
		$subdir = $params->get('items_commentdir', 'ASC'); //comments
		if ($order == 'ordering'){
			if ($dir == 'DESC') {
				$order = 'rgt';
			} else {
				$order = 'lft';
			}
		}
		
		$this->setState('filter.ordering', $order);
		$this->setState('filter.direction', $dir);
		
		$this->setState('filter.subdirection', $subdir);
		
		if (empty($this->_data)) {	
			$level =  $params->get('display_comments', 1);
			$query = $this->_buildAllQuery($level, $start, $limit);
			$this->_data = $this->_getList($query);
		}
		
		return $this->_data;
    }
    
    
	function getTotal()
	{
		// Load the content if it doesn't already exist
		if (empty($this->_total)) {
			$query = $this->_buildL1Query();
			$this->_total = $this->_getListCount($query);       
		}
		return $this->_total;
	}
	
	
	function getPagination()
	{
		// Load the content if it doesn't already exist
		if (empty($this->_pagination)) {
			jimport( 'joomla.html.pagination' );
			
			//$this->_pagination = new JPagination($this->getTotal(), $this->getState('list.start'), $this->getState('list.limit') );
			$this->_pagination = new PhocaGuestbookPaginationPosts( $this->getTotal(), $this->getState('list.start'), $this->getState('list.limit') );
		}
				
		return $this->_pagination;
	}
	
    
	public function getGuestbook()
	{
		if (!is_object($this->_guestbook)) {
			$categories = JCategories::getInstance('Phocaguestbook');

			$this->_guestbook = $categories->get($this->getState('category.id', 'root'));

			// Compute selected asset permissions.
			if (is_object($this->_guestbook)) {
				$user	= JFactory::getUser();
				$groups	= $user->getAuthorisedViewLevels();
				$userId	= $user->get('id');
				$asset	= 'com_phocaguestbook.category.'.$this->getState('category.id', 'root');

				// Check post permission.
				if ($user->authorise('core.create.post', $asset)) {
					$this->_guestbook->getParams()->set('access-post', true);
				}
				// Check reply permission.
				if ($user->authorise('core.create', $asset)) {
					$this->_guestbook->getParams()->set('access-reply', true);
				}
				// Check edit permission.
				if ($user->authorise('core.edit', $asset)) {
					$this->_guestbook->getParams()->set('access-edit', true);
				}
				// Check edit state permission.
				if ($user->authorise('core.edit.own', $asset)) {
					$this->_guestbook->getParams()->set('access-own', true);
				}
				// Check edit state permission.
				if ($user->authorise('core.edit.state', $asset)) {
					$this->_guestbook->getParams()->set('access-state', true);
				}
				// Check delete permission.
				if ($user->authorise('core.delete', $asset)) {
					$this->_guestbook->getParams()->set('access-delete', true);
				}
				// Check view permissions (on access level)
				if (in_array($this->_guestbook->access, $groups)) {
					$this->_guestbook->getParams()->set('access-view', true);
				}
				
			} else {
				return false;
			}
		}
	
		return $this->_guestbook;
	}
	
	
	function _buildL1Query() {
		$filter_order = $this->getState('filter.ordering', 'lft');
        $filter_order_dir = $this->getState('filter.direction', 'ASC');
        $filter_order = JFilterInput::getInstance()->clean($filter_order, 'cmd');
        $filter_order_dir = JFilterInput::getInstance()->clean($filter_order_dir, 'word');
        
		$db			= JFactory::getDBO();
		$where 		= array();
		$where[]	= 'catid = '.$this->getState('category.id');
		if ($this->_guestbook->getParams()->get('access-state') != true) {
			$where[]	= 'published = 1';
		}
		
		// Filter by language
		if ($this->getState('filter.language')) {
			$where[] =  'language IN ('.$db->Quote(JFactory::getLanguage()->getTag()).','.$db->Quote('*').')';
		}		
		
		$where[]	= 'level = 1';
		
		// We need to get a list of all weblinks in the given category
        $query = $db->getQuery(true)
			->from($db->quoteName('#__phocaguestbook_items'))
			->select('*')
			->where($where)
			->order($filter_order.' '.$filter_order_dir);
            
		return $query;
	}
	
	function _buildAllQuery($level, $start=0, $limit=5) {
		$filter_order = $this->getState('filter.ordering', 'lft');
        $filter_order_dir = $this->getState('filter.direction', 'ASC');
		
        $filter_order = JFilterInput::getInstance()->clean($filter_order, 'cmd');
        $filter_order_dir = JFilterInput::getInstance()->clean($filter_order_dir, 'word');
        
        $filter_sub_order_dir = $this->getState('filter.subdirection', 'ASC');
		if ($filter_sub_order_dir == 'DESC') {
			$subfilter_order = 'rgt';
			$subfilter_dir   = 'DESC';
		} else {
			$subfilter_order = 'lft';
			$subfilter_dir   = 'ASC';
		}
		

		
		$this->setState('filter.ordering', $filter_order);
        $this->setState('filter.direction', $filter_order_dir);
        $this->setState('filter.subdirection', $filter_sub_order_dir);
		
		
        
		$db			= JFactory::getDBO();
		$where		= array();
		$whereb		= array();
		$whered		= array();
		
		$where[]	= 'catid = '.$this->getState('category.id');
		$whereb[]	= 'b.catid = '.$this->getState('category.id');
		$whered[]	= 'd.catid = '.$this->getState('category.id');
		if ($this->_guestbook->getParams()->get('access-state') != true) {
			$where[]	= 'published = 1';
			$whereb[]	= 'b.published = 1';
			$whered[]	= 'd.published = 1';
		}
		
		// Filter by language
		if ($this->getState('filter.language')) {
			$where[]  =  'language IN ('.$db->Quote(JFactory::getLanguage()->getTag()).','.$db->Quote('*').')';
			$whereb[] =  'b.language IN ('.$db->Quote(JFactory::getLanguage()->getTag()).','.$db->Quote('*').')';
			$whered[] =  'd.language IN ('.$db->Quote(JFactory::getLanguage()->getTag()).','.$db->Quote('*').')';
		}
		
		$where[] = 'level = 1';
				
		$queryL1 = '	SELECT *, '.$filter_order.' AS mainOrd FROM '.$this->_db->quoteName('#__phocaguestbook_items').' WHERE '.implode(' AND ', $where).' ORDER BY '.$filter_order.' '.$filter_order_dir.' LIMIT '.$start.','.$limit.' ';
		$orderL1 = '';
		$queryL2 = '    SELECT mainOrd AS aOrd, b.'.$subfilter_order.' AS bOrd, b.*FROM ('. $queryL1 .')  AS a JOIN '.$this->_db->quoteName('#__phocaguestbook_items').' AS b ON (a.id=b.parent_id AND b.level=2) OR a.id=b.id WHERE '.implode(' AND ', $whereb).' ';
		$orderL2 = '     ORDER BY aOrd '.$filter_order_dir.', bOrd '.$subfilter_dir;
		$queryL3 = '    SELECT aOrd, bOrd, d.*, d.'.$subfilter_order.' AS dOrd FROM ('. $queryL2 .')  AS c JOIN '.$this->_db->quoteName('#__phocaguestbook_items').' AS d ON (c.id=d.parent_id AND d.level>=3) OR c.id=d.id WHERE '.implode(' AND ', $whered).' ';
		$orderL3 = '     ORDER BY aOrd '.$filter_order_dir.', bOrd '.$subfilter_dir.', dOrd '.$subfilter_dir.' ';

		switch ($level){
			case 0:
			case 1:
				$query = $queryL1 . $orderL1;
				break;
			case 2:
				$query = $queryL2 . $orderL2;
				break;
			default:
				$query = $queryL3 . $orderL3;
				break;
		}

		return $query;
	}
	
	
	public function delete($cid = 0) {
		$where 		= array();
		$where[]	= 'id = '.$cid;	
		
		$query = $this->_db->getQuery(true)
			->from($this->_db->quoteName('#__phocaguestbook_items'))
			->delete()
			->where($where);
	
		$this->_db->setQuery( $query );
		if(!$this->_db->query()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		return true;
	}
	
	public function publish($cid = 0, $catid = 0, $publish = 1) {
		$where 		= array();
		$where[]	= 'id = '.$cid;	
		$where[]	= 'catid = '.$catid;	
	
		$set 		= array();
		$set[]		= 'published = ' . $publish;	
		
		$query = $this->_db->getQuery(true)
			->update($this->_db->quoteName('#__phocaguestbook_items'))
			->set($set)
			->where($where);
		
		$this->_db->setQuery( $query );
		if (!$this->_db->query()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		return true;
	}
	
	protected function checkReference() {
		
		$where 		= array();
		$where[]	= 'id = 1';	
		$where[]	= 'catid = 0';
		$where[]	= 'parent_id = 0';
		
		$query = $this->_db->getQuery(true)
			->from($this->_db->quoteName('#__phocaguestbook_items'))
			->select('id')
			->where($where);
			
		$this->_db->setQuery($query, 0, 1);

		if ($this->_db->loadResult()) {
			return true;
		} else {
			// Standard installation query
			
			$query = "INSERT INTO `#__phocaguestbook_items` (`id`, `catid`, `parent_id`, `lft`, `rgt`, `level`, `path`, `username`, `userid`, `email`, `homesite`, `ip`, `title`, `alias`, `content`, `date`, `published`, `checked_out`, `checked_out_time`, `params`, `language`) VALUES (1, 0, 0, 0, 1, 0, '', 'ROOT', 0, '', '', '', 'root', 'root', '', '0000-00-00 00:00:00', 0, 0, '0000-00-00 00:00:00', '', '*');";
			$this->_db->setQuery($query);
			if (!$this->_db->query()) {
				$this->setError('Database Error Check Reference');
				return false;
			}
			return true;
		}
	}
	
}
