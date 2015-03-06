<?php
/**
 * @package    phocaguestbook
 * @subpackage Views
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
 
defined('_JEXEC') or die('Restricted access');

jimport('joomla.html.html.bootstrap');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.tooltip');
JHTML::_('behavior.modal');

if($this->params->get('custom_color')) :?>
<style type="text/css">
.pgb_font {
	 color:<?php echo $this->params->get('font_color');?>;
}
.pgb_sec_font {
	 color:<?php echo $this->params->get('second_font_color');?>;
}
.pgb_thi_font {
	 color:<?php echo $this->params->get('third_font_color');?>;
}
.pgb_background {
	 background:<?php echo $this->params->get('background_color');?>;
	 border:1px solid <?php echo $this->params->get('border_color');?>;
}
.pgb_border {
	border:1px solid <?php echo $this->params->get('border_color');?>;	 
}
</style>
<?php endif;

if ($this->params->get('show_form')) : ?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'phocaguestbook.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
			Joomla.submitform(task);
		} else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
		}
	}
</script>
<?php endif; ?>
<div id="phocaguestbook" class="guestbook<?php echo$this->params->get( 'pageclass_sfx' );?>">
<?php

// - - - - - - - - - - -
// Header
// - - - - - - - - - - -
if ( $this->params->get( 'show_page_heading' ) ) : ?>
	<div class="page-header">
		<h1>
			<?php echo $this->escape($this->params->get('page_heading')); ?>
		</h1>
	</div>
<?php 
	//image not supported yet -> add "image" to xml file
	if ( @$this->image || @$this->guestbooks->description ) : ?>
	<div class="well pgb_background guestbook-description">
	<?php
	/*if ( isset($this->tmpl['image']) ) {
		echo $this->tmpl['image'];
	}*/
	echo $this->guestbooks->description;
	?>
	</div>
<?php endif; endif;

// - - - - - - - - - - -
// Page / Different positions
// - - - - - - - - - - - 
if ($this->params->get('form_style') == 1){
	$formStyle = 'form'; 
} else {
	$formStyle = 'form_classic'; 
}

// Display Page (Posts, Items, Form)
// Forms:
//  If position = 0 --> Form is top, Messages bottom
//  If position = 1 --> Form is bottom, Messages top, 
//  If position = 2 --> Use tabs for Message and Form
switch ($this->params->get('form_position')) {
	case 0:
		//echo $this->loadTemplate('form');
		echo $this->loadTemplate($formStyle);
		echo $this->loadTemplate('posts');
		break;
	case 1:
		echo $this->loadTemplate('posts');
		echo $this->loadTemplate($formStyle);
		break;
	case 2:
	default:?>
		<div class="tabbable">
			<ul class="nav nav-tabs">
				<?php if ($this->params->get('show_form'))  :?><li<?php if ( $this->params->get('tab_active_form')) {echo " class=\"active\"";}?>><a href="#pgbTabForm"  data-toggle="tab"><?php echo JText::_('COM_PHOCAGUESTBOOK_POST_MESSAGE');?></a></li><?php endif;?>
				<?php if ($this->params->get('show_posts')) :?><li<?php if (!$this->params->get('tab_active_form')) {echo " class=\"active\"";}?>><a href="#pgbTabPosts" data-toggle="tab"><?php echo JText::_('COM_PHOCAGUESTBOOK_ITEMS');?></a></li><?php endif;?>
			</ul>
			<div class="tab-content">
				<div class="tab-pane<?php if ( $this->params->get('tab_active_form')) {echo " active";}?>" id="pgbTabForm">
					<?php echo $this->loadTemplate($formStyle); ?>
				</div>
				<div class="tab-pane<?php if (!$this->params->get('tab_active_form')) {echo " active";}?>" id="pgbTabPosts">
					<?php echo $this->loadTemplate('posts'); ?>
				</div>
			</div>
		</div>
	<?php
	break;
}

echo '</div>';
