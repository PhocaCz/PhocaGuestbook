<?php
/**
 * @package    phocaguestbook
 * @subpackage Views
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;


$app				= Factory::getApplication();
$values = &$this->values;

		//Maximum of links in the message
		$rand 				= '%phoca' . mt_rand(0,1000) * time() . 'phoca%';
		$ahref_replace 		= "<a ".$rand."=";
		$ahref_search		= "/<a ".$rand."=/";
		$values->content	= preg_replace ("/<a href=/", $ahref_replace, $values->content, $this->params->get('max_url', 5));
		$values->content	= preg_replace ("/\<a href\=.*?\>(.*?)\<\/a\>/",	"$1", $values->content);
		$values->content	= preg_replace ($ahref_search, "<a href=", $values->content);
		$withTitle = $this->params->get('display_comment_name', 1)   ||
					 $this->params->get('display_comment_email', 1) ||
					($this->params->get('display_comment_subject', 1) && $values->title != '') ||
					 $this->params->get('display_comment_website', 1);

		$withFoot  = $this->params->get('display_comment_date', 1) ||
		             $this->params->get('access-delete') ||
		             $this->params->get('access-state'); ?>

		<div class="pgb-comment"> <?php
		if ($withTitle) :
		?><h5 class="pgtitle"><?php
		//TITLE START

		//!!! username saved in database can be username or name
		$sep = 0;
		if ($this->params->get('display_comment_name', 1)) {
			if ($values->username != '') {
				echo phocaguestbookHelperFront::wordDelete($values->username, 40, '...');
				$sep = 1;
			}
		}
		if ($this->params->get('display_comment_email', 1)) {
			if ($values->email != '') {
				if ($sep == 1) {
					echo ' ( '. HTMLHelper::_( 'email.cloak', phocaguestbookHelperFront::wordDelete($values->email, 50, '...') ).' )';
					$sep = 1;
				} else {
					echo HTMLHelper::_( 'email.cloak', phocaguestbookHelperFront::wordDelete($values->email, 50, '...') );
					$sep = 1;
				}
			}
		}
		if ($this->params->get('display_comment_subject', 1) && $values->title != '') {
			if ($sep == 1) {
				echo ': ';
			}
			echo phocaguestbookHelperFront::wordDelete($values->title, 100, '...');
		}
		if ($this->params->get('display_comment_website', 1)) {
			if ($values->homesite != '') {

				if ($values->title == '' && $values->email == '' && $values->username == '') {
				} else {
					echo ' <br />';
				}

				echo ' <span><a href="'.$values->homesite.'">'.phocaguestbookHelperFront::wordDelete($values->homesite, 50, '...').'</a></span>';
			}
		}
		?>
		</h5>
		<?php
		endif; //WithTitle END

		// SECURITY
		// Open a tag protection
		$a_count 		= substr_count(strtolower($values->content), "<a");
		$a_end_count 	= substr_count(strtolower($values->content), "</a>");
		$quote_count	= substr_count(strtolower($values->content), "\"");

		if ($quote_count%2!=0) {
			$end_quote = "\""; // close the " if it is open
		} else {
			$end_quote = "";
		}

		if ($a_count > $a_end_count) {
			$end_a = "></a>"; // close the a tag if there is a open a tag
							  // in case <a href> ... <a href ></a>
							  // in case <a ... <a href >></a>
		} else {
			$end_a = "";
		}

		//CONTENT START
		?>
		<blockquote class="pgblockquote pgb_sec_font"><div class="pgb-content-inside">
		<?php echo $values->content . $end_quote .$end_a . '</div>';

		if ($withFoot) :?>
		<hr  class="hr-condensed pgb_border"/>
		<small class="pgb_thi_font">
			<?php if ($this->params->get('display_comment_date', 1)) {
				echo HTMLHelper::_('date',  $values->date, Text::_( $this->params->get('date_format', 'DATE_FORMAT_LC') ) );
				}
		if ($this->params->get('access-delete') || $this->params->get('access-state')) : ?>
		<div class="pull-right">
		<?php
		if ($this->params->get('access-delete')) : ?>
			<a href="<?php echo Route::_('index.php?option=com_phocaguestbook&task=phocaguestbook.delete&id='.$this->guestbooks->id.'&Itemid='.$app->input->get('Itemid', 0, 'int').'&controller=phocaguestbook&mid='.$values->id.'&start='.$this->pagination->limitstart)?>" onclick="return confirm(\'<?php echo Text::_( 'COM_PHOCAGUESTBOOK_WARNING_DELETE_ITEM' )?>\');" title="<?php echo Text::_('COM_PHOCAGUESTBOOK_DELETE');?>" class="btn hasTooltip">
			<i class="glyphicon glyphicon-trash icon-trash"></i>
			</a>
		<?php endif;
		if ($this->params->get('access-state')) :
			if ($values->published==1) :?>
				<a href="<?php echo Route::_('index.php?option=com_phocaguestbook&task=phocaguestbook.unpublish&id='.$this->guestbooks->id.'&Itemid='.$app->input->get('Itemid', 0, 'int').'&controller=phocaguestbook&mid='.$values->id.'&start='.$this->pagination->limitstart)?>" title="<?php echo Text::_('COM_PHOCAGUESTBOOK_UNPUBLISH');?>" class="btn hasTooltip">
				<i class="glyphicon glyphicon-remove icon-remove"></i></a>
			<?php else:  ?>
				<a href="<?php echo Route::_('index.php?option=com_phocaguestbook&task=phocaguestbook.publish&id='.$this->guestbooks->id.'&Itemid='.$app->input->get('Itemid', 0, 'int').'&controller=phocaguestbook&mid='.$values->id.'&start='.$this->pagination->limitstart)?>" title="<?php echo Text::_('COM_PHOCAGUESTBOOK_PUBLISH');?>" class="btn hasTooltip">
				<i class="glyphicon glyphicon-ok icon-ok"></i></a>
			<?php endif; ?>
		<?php endif; ?>
		</div>
		<?php endif; ?>
		</small>
		<?php endif; ?>
		</blockquote>
		</div>



