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
class PhocaguestbookModelPhocaguestbook extends JModelAdmin
{
	protected $_data;
	
	/**
	 * Prepare and sanitise the table data prior to saving.
	 */
	protected function prepareTable($table)
	{
		jimport('joomla.filter.output');
		
		// Set title and alias
		$table->title		= htmlspecialchars_decode($table->title, ENT_QUOTES);
		$table->alias		= JApplication::stringURLSafe($table->alias);
		if (empty($table->alias)) {
			$table->alias = JApplication::stringURLSafe($table->title);
		}

		// Reorder the articles within the category so the new article is first
		if (empty($table->id)) {
			//$table->reorder('catid = '.(int) $table->catid.' AND published >= 0');
			$table->setLocation($table->parent_id, 'last-child');
		}
	}
	
	
    /**
     * Returns a reference to the a Table object, always creating it.
     * @internal param \The $type table type to instantiate
     */
    public function getTable($type = 'phocaguestbook', $prefix = 'Table', $config = array())
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
        // Get the form.
        $form = $this->loadForm('com_phocaguestbook.phocaguestbook', 'phocaguestbook'
        , array('control' => 'jform', 'load_data' => $loadData));

        if(empty($form))
        {
            return false;
        }
        
		$jinput = JFactory::getApplication()->input;
		// The front end calls this model and uses a_id to avoid id clashes so we need to check for that first.
		if ($jinput->get('g_id'))
		{
			$id = $jinput->get('g_id', 0);
		}
		// The back end uses id so we use that the rest of the time and set it to 0 by default.
		else
		{
			$id = $jinput->get('id', 0);
		}
		
		// Determine correct permissions to check.
		if ($this->getState('phocaguestbook.id'))
		{
			$id = $this->getState('phocaguestbook.id');
			// Existing record. Can only edit in selected categories.
			$form->setFieldAttribute('catid', 'action', 'core.edit');
			// Existing record. Can only edit own articles in selected categories.
			$form->setFieldAttribute('catid', 'action', 'core.edit.own');
		}
		else
		{
			// New record. Can only create in selected categories.
			$form->setFieldAttribute('catid', 'action', 'core.create');
		}

		$user = JFactory::getUser();

		// Check for existing article.
		// Modify the form based on Edit State access controls.
		if ($id != 0 && (!$user->authorise('core.edit.state', 'com_phocaguestbook.phocaguestbook.'.(int) $id))
		|| ($id == 0 && !$user->authorise('core.edit.state', 'com_phocaguestbook'))
		)
		{
			// Disable fields for display.
			$form->setFieldAttribute('ordering', 'disabled', 'true');
			$form->setFieldAttribute('state', 'disabled', 'true');

			// Disable fields while saving.
			// The controller has already verified this is an article you can edit.
			$form->setFieldAttribute('ordering', 'filter', 'unset');
			$form->setFieldAttribute('state', 'filter', 'unset');

		}
		
		
		$data = $this->loadFormData();
		if ($data->parent_id > 1){
			// New record. Can only create in selected categories.
			$form->setFieldAttribute('catid', 'readonly', 'true');
		}
		

		return $form;
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return mixed The data for the form.
     */
    protected function loadFormData()
    {
		if (empty($this->_data)) {
			// Check the session for previously entered form data.
			$app  = JFactory::getApplication();
			$this->_data = $app->getUserState('com_phocaguestbook.edit.phocaguestbook.data', array());
			
			if(empty($this->_data))
			{
				$this->_data = $this->getItem();
				
				// Prime some default values.
				if ($this->getState('phocaguestbook.id') == 0) {
					$this->_data->set('catid', $app->input->getInt('catid', $app->getUserState('com_phocaguestbook.phocaguestbook.filter.category_id')));
					
					$this->_data->set('parent_id', $app->input->getInt('parentid', 1));
				}
			}
		}

        return $this->_data;
    }
    
    
	/**
	 * A protected method to get a set of ordering conditions.
	 */
	protected function getReorderConditions($table)
	{
		$condition = array();
		$condition[] = 'catid = '.(int) $table->catid;
		return $condition;
	}
	
	
	
	public function saveorder($idArray = null, $lft_array = null)
	{
		// Get an instance of the table object.
		$table = $this->getTable();

		if (!$table->saveorder($idArray, $lft_array))
		{
			$this->setError($table->getError());
			return false;
		}

		// Clear the cache
		//$this->cleanCache();

		return true;
	}
	
}
