<?php
/**
 * @package    phocaguestbook
 * @subpackage Views
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die;

class PhocaguestbookViewguestbook extends JViewLegacy
{
	public function display($tpl = null)
	{
		$app       = JFactory::getApplication();
		$doc       = JFactory::getDocument();
		$params    = $app->getParams();
		$siteEmail = $app->getCfg('mailfrom');

		// Get some data from the model
		$app->input->set('limit', $app->getCfg('feed_limit'));

		// - - - - - - - - - - -
		// Get constant data from model
		$state		= $this->get('State');
		$guestbooks	= $this->get('Guestbook'); // = getCategory
		// Check for errors.
		if ($guestbooks == false) {
			return JError::raiseError(404, JText::_('COM_PHOCAGUESTBOOK_GUESTBOOK_NOT_FOUND'));
		}

		// Load the parameters. 
		// Merge Global => GUESTBOOK => Menu Item params into new object in view
		$applparams = $app->getParams();		
		$bookparams = new JRegistry;
		$menuParams = new JRegistry;
		$bookparams->loadString($guestbooks->get('params'));
		if ($menu = $app->getMenu()->getActive()) {
			$menuParams->loadString($menu->params);
		} 
		
		$params = clone $applparams;
		$params->merge($bookparams);
		$params->merge($menuParams);
		$state->set('params', $params);

		// Check whether category access level allows access.
		if (!$params->get('access-view')) {
			return JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
		}

		// - - - - - - - - - - -
		// Get data from model, depending on parmas
		$items		= $this->get('Data');
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseWarning(500, implode("\n", $errors));
			return false;
		}		
		
		//SHOW ITEMS
		$doc->link = JRoute::_(PhocaguestbookHelperRoute::getCategoryRoute($guestbooks->id+10));
		
		if ($params->get('display_posts')) {
			foreach ($items as $key => &$item) {

				if ($params->get('display_hidden_word') != 1) {	
					$fwfa	= explode( ',', trim( $params->get( 'forbidden_word_filter', '' ) ) );
					$fwwfa	= explode( ',', trim( $params->get( 'forbidden_whole_word_filter', '' ) ) );

				
					// Forbidden Word Filter
					// Believe or not - it is more faster to replace items than the whole content :-)
					foreach ($fwfa as $values2) {
						if (function_exists('str_ireplace')) {
							$item->username 	= str_ireplace (trim($values2), '***', $item->username);
							$item->title		= str_ireplace (trim($values2), '***', $item->title);
							$item->content		= str_ireplace (trim($values2), '***', $item->content);
						} else {		
							$item->username 	= str_replace (trim($values2), '***', $item->username);
							$item->title		= str_replace (trim($values2), '***', $item->title);
							$item->content		= str_replace (trim($values2), '***', $item->content);
						}
					}
				
					
					//Forbidden Whole Word Filter
					foreach ($fwwfa as $values2) {
						if ($values2 !='') {
							//$values3			= "/([\. ])".$values3."([\. ])/";
							$values2			= "/(^|[^a-zA-Z0-9_]){1}(".preg_quote(($values2),"/").")($|[^a-zA-Z0-9_]){1}/i";
							$item->username 	= preg_replace ($values2, "\\1***\\3", $item->username);// \\2
							$item->title		= preg_replace ($values2, "\\1***\\3", $item->title);
							$item->content		= preg_replace ($values2, "\\1***\\3", $item->content);
						}
					}
				}


				// Strip html from feed item title
				$title = $this->escape($item->title);
				$title = html_entity_decode($title, ENT_COMPAT, 'UTF-8');

				// Get description, author and date
				$description = $item->content;
				$author = $params->get('display_name') ? $item->username : $params->get('predefined_name');
				@$date = date('r', strtotime($item->date));

				// Load individual item creator class
				$item           = new JFeedItem;
				$item->title    = $title;
				$item->link     = $link;	//always use link to guestbook
				$item->date     = $date;
				$item->category = $guestbooks->title;
				$item->author   = $author;
				$item->authorEmail = $siteEmail;	//always page email
				$item->description	= '<div class="feed-description">'.$description.'</div>';				
				
				// Loads item info into rss array
				$doc->addItem($item);		
			}
			
		}
	}
}
