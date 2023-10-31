<?php
/**
/**
 * @package    phocaguestbook
 * @subpackage Views
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
//-- No direct access
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Factory;

// Include the component HTML helpers.
HtmlHelper::addIncludePath(JPATH_COMPONENT.'/helpers/html');



// Create shortcut to parameters.
$params = $this->state->get('params');
$params = $params->toArray();


Factory::getDocument()->addScriptDeclaration(

'Joomla.submitbutton = function(task) {
	if (task != "'. $this->t['task'].'.cancel" && document.getElementById("jform_catid").value == "") {
		alert("'. Text::_('JGLOBAL_VALIDATION_FORM_FAILED', true) . ' - '. Text::_('COM_PHOCAGUESTBOOK_CATEGORY_NOT_SELECTED', true).'");
	} else if (task == "'. $this->t['task'].'.cancel" || document.formvalidator.isValid(document.getElementById("item-form"))) {
		Joomla.submitform(task, document.getElementById("item-form"));
	} else {
        alert(\''. Text::_('JGLOBAL_VALIDATION_FORM_FAILED', true).'\');
	}
}'

);



?>
<form action="<?php echo Route::_('index.php?option=com_phocaguestbook&layout=edit&id='.(int)$this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-validate">
	<div class="row-fluid">
		<div class="span10 form-horizontal">

			<div class="row-fluid">
				<div class="span6">
					<h4><?php echo Text::_('COM_PHOCAGUESTBOOK_ADDRESS');?></h4>

					<?php  $formArray = array ('username', 'email', 'homesite');?>
					<?php foreach($formArray as $value): ?>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel($value); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput($value); ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<div class="span6">
					<?php  $formArray = array ('date', 'ip', 'id', 'parent_id');?>
					<?php foreach($formArray as $value): ?>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel($value); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput($value); ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>

			<fieldset class="adminform">
				<h4><?php echo Text::_('COM_PHOCAGUESTBOOK_ENTRY');?></h4>
				<div class="control-group form-inline">
					<div class="control-label">
						<?php echo $this->form->getLabel('title'); ?>
					</div>
					<div class="control">
						<?php echo $this->form->getInput('title'); ?>
					</div>
				</div>
				<div class="control-group form-inline">
					<div class="control-label">
						<?php echo $this->form->getLabel('catid'); ?>
					</div>
					<div class="control">
						<?php echo $this->form->getInput('catid'); ?>
					</div>
				</div>
				<?php echo $this->form->getInput('content'); ?>
			</fieldset>
		</div>
		<div class="span2">
			<h4><?php echo Text::_('JDETAILS');?></h4>
			<hr />
			<fieldset class="form-vertical">
				<div class="control-group">
					<div class="controls">
						<?php echo $this->form->getValue('title'); ?>
					</div>
				</div>

				<div class="control-group">
					<?php echo $this->form->getLabel('published'); ?>
					<div class="controls">
						<?php echo $this->form->getInput('published'); ?>
					</div>
				</div>
				<div class="control-group">
					<?php echo $this->form->getLabel('language'); ?>
					<div class="controls">
						<?php echo $this->form->getInput('language'); ?>
					</div>
				</div>
			</fieldset>
		</div>
	</div>
    <div>
        <input type="hidden" name="task" value="phocaguestbook.edit" />
		<input type="hidden" name="return" value="<?php /*echo $input->getCmd('return');*/?>" />
		<?php echo HTMLHelper::_('form.token'); ?>
    </div>
</form>
