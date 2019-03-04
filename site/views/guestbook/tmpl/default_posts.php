<?php
/**
 * @package    phocaguestbook
 * @subpackage Views
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');

$prevLevel = 1;
$init = true;

// - - - - - - - - - - -
// Messages - create and correct Messages (Posts, Items)
// - - - - - - - - - - -
if ($this->params->get('show_posts') == 1) {
	echo '<div>';

	if (!empty($this->items)) {

		foreach ($this->items as $key => $values) {
			$this->values = &$values;


			for($i = $prevLevel - $values->level; $i > 0; $i--){
				//close previous post(s)
				echo '</div>';
			}

			if ($prevLevel >= 1 && $values->level == 1){
				echo '</div><div class="well well-small pgwell pgb_background pgb_font">';
			}


			//todo unterscheiden zwischen den levels

			for($i = $values->level - $prevLevel; $i > 0; $i--){
				//open post(s)
				echo '<div class="pgsubwell pgsubwell'.$values->level.'">';
			}

			if ($values->level == 1)
				echo $this->loadTemplate('post');
			else
				echo $this->loadTemplate('comment');

			$prevLevel = $values->level;
		//end foreach
		}

		for($i = $prevLevel; $i > 0; $i--){
			//close previous post(s)
			echo '</div>';
		}

		// - - - - - - - - - - -
		// Pagination
		// - - - - - - - - - - -
	/*	if ($this->params->get('show_pagination')) {
			echo $this->pagination->getListFooter();


			echo $this->pagination->getPagesCounter();
		}
		if ($this->params->get('show_pagination_limit')) {
			//TODO
			echo JText::_('Display Num').'&nbsp;';
			 echo $this->pagination->getLimitBox();
		}*/

		$uri = \Joomla\CMS\Uri\Uri::getInstance();
		$form2 = '';
		$form2 = '<div><form action="'.htmlspecialchars($uri->toString()).'" method="post" name="adminForm" id="pgbadminForm">';
		if (count($this->items)) {
			$form2 .='<div class="pgcenter"><div class="pagination">';
			if ($this->params->get('show_pagination_limit')) {
				$form2 .= '<div class="pginline">'.JText::_('COM_PHOCAGUESTBOOK_DISPLAY_NUM') .'&nbsp;'.$this->pagination->getLimitBox().'</div>';
			}
			if ($this->params->get('show_pagination')) {
				$form2 .= '<div style="margin:0 10px 0 10px;" class="sectiontablefooter'.$this->params->get( 'pageclass_sfx' ).'" id="pg-pagination" >'.$this->pagination->getPagesLinks().'</div><div style="margin:0 10px 0 10px;display:inline;" class="pagecounter">'.$this->pagination->getPagesCounter().'</div>';

			}
			$form2 .='</div></div>';
		}
		$form2 .= '</form></div>';

		if (count($this->items) && $this->params->get('show_pagination') && $this->pagination->getPagesLinks()) {
			$form2 .= '<div class="pg-pagination-bottom">&nbsp;</div>';
		}
		echo $form2;

	} else {
		//echo '<div>'.// start tag at the top
		echo JText::_('COM_PHOCAGUESTBOOK_THERE_IS_NO_POST').'</div>';
	}

	echo $this->params->get('pgbinfo');


// end show posts
}

?>
