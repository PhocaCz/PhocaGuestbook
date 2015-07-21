<?php
/*
 * @package Joomla 1.5
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Guestbook
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
 
function PhocaGuestbookBuildRoute(&$query) {
	
	$segments = array();

	// get a menu item based on Itemid or currently active
	$app	= JFactory::getApplication();
	$menu	= $app->getMenu();
	$params = JComponentHelper::getParams('com_phocaguestbook');
	$advanced = $params->get('sef_advanced_link', 0);

	if (empty($query['Itemid'])) {
		$menuItem = $menu->getActive();
	}
	else {
		$menuItem = $menu->getItem($query['Itemid']);
	}
	$mView	= (empty($menuItem->query['view'])) ? null : $menuItem->query['view'];
	//$mCatid	= (empty($menuItem->query['catid'])) ? null : $menuItem->query['catid'];
	$mId	= (empty($menuItem->query['cid'])) ? null : $menuItem->query['cid'];

	if (isset($query['view']))
	{
		$view = $query['view'];
		if (empty($query['Itemid'])) {
			$segments[] = $query['view'];
		}
		unset($query['view']);
	};

	// are we dealing with an newsfeed that is attached to a menu item?
	if (isset($query['view']) && ($mView == $query['view']) and (isset($query['cid'])) and ($mId == intval($query['cid']))) {
		unset($query['view']);
		//unset($query['catid']);
		unset($query['cid']);
		return $segments;
	}

	if (isset($view) and ($view == 'guestbook' or $view == 'guestbooki')) {
		if ((isset($query['cid']) && $mId != intval($query['cid'])) || $mView != $view) {
			
			if($view == 'guestbook')
			{
				if($advanced)
				{
					list($tmp, $id) = explode(':', $query['cid'], 2);
				} else {
					$id = $query['cid'];
				}
				$segments[] = $id;
			}
		}
		unset($query['cid']);
		unset($query['catid']);
	}

	if (isset($query['layout']))
	{
		if (!empty($query['Itemid']) && isset($menuItem->query['layout']))
		{
			if ($query['layout'] == $menuItem->query['layout']) {

				unset($query['layout']);
			}
		}
		else
		{
			if ($query['layout'] == 'default') {
				unset($query['layout']);
			}
		}
	};

	return $segments;

}

function PhocaGuestbookParseRoute($segments) {
	$vars = array();

	//Get the active menu item.
	$app	= JFactory::getApplication();
	$menu	= $app->getMenu();
	$item	= $menu->getActive();
	$params = JComponentHelper::getParams('com_phocaguestbook');
	$advanced = $params->get('sef_advanced_link', 0);

	// Count route segments
	$count = count($segments);

	// Standard routing for newsfeeds.
	if (!isset($item))
	{
		$vars['view']	= $segments[0];
		$vars['cid']		= $segments[$count - 1];
		return $vars;
	}


	return $vars;
}
?>