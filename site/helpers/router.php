<?php
/**
 * @package    phocaguestbook
 * @subpackage Helpers
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */ 
 defined('_JEXEC') or die;
use Joomla\CMS\Categories\CategoryNode;
use Joomla\CMS\Categories\Categories;
use Joomla\CMS\Factory;
use Joomla\CMS\Component\ComponentHelper;
 

 /**
 * Phocaguestbook Component Route Helper
 */
abstract class PhocaguestbookHelperRoute
{
	protected static $lookup;

	/**
	 * @param	int	The route of the content item
	 */
	public static function getCategoryRoute($catid)
	{
		if ($catid instanceof CategoryNode)
		{
			$id = $catid->id;
			$category = $catid;
		}
		else
		{
			$id = (int) $catid;
			$category = Categories::getInstance('Phocaguestbook')->get($id);
		}


		if($id < 1)
		{
			$link = '';
		}
		else
		{
			$needles = array(
				'phocaguestbook' => array($id)
			);

			if ($item = self::_findItem($needles))
			{
				$link = 'index.php?Itemid='.$item;
			}
			else
			{
				//Create the link
				$link = 'index.php?option=com_phocaguestbook&view=phocaguestbook&cid='.$id;
				if($category)
				{
					$catids = array_reverse($category->getPath());
					$needles = array(
						'phocaguestbook' => $catids
					);
					if ($item = self::_findItem($needles)) {
						$link .= '&Itemid='.$item;
					}
					elseif ($item = self::_findItem()) {
						$link .= '&Itemid='.$item;
					}
				}
			}
		}

		return $link;
	}
	
	
	protected static function _findItem($needles = null)
	{
		$app		= Factory::getApplication();
		$menus		= $app->getMenu('site');



		// Prepare the reverse lookup array.
		if (self::$lookup === null)
		{
			self::$lookup = array();

			$component	= ComponentHelper::getComponent('com_phocaguestbook');
			$items		= $menus->getItems('component_id', $component->id);
						
			foreach ($items as $item)
			{
				if (isset($item->query) && isset($item->query['view']))
				{		
					$view = $item->query['view'];
					if (!isset(self::$lookup[$view])) {
						self::$lookup[$view] = array();
					}
					if (isset($item->query['cid'])) {
						self::$lookup[$view][$item->query['cid']] = $item->id;
					}
				}
			}
		}
		
		if ($needles)
		{
			foreach ($needles as $view => $ids)
			{
				
				if (isset(self::$lookup[$view]))
				{
					foreach($ids as $id)
					{
						if (isset(self::$lookup[$view][(int) $id])) {
							return self::$lookup[$view][(int) $id];
						}
					}
				}
			}
		}
		else
		{
			$active = $menus->getActive();
			if ($active && $active->component == 'com_phocaguestbook') {
				return $active->id;
			}
		}

		return null;
	}

}
