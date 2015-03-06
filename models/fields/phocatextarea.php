<?php
/**
 * @package    phocaguestbook
 * @subpackage Models
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('JPATH_BASE') or die;
jimport('joomla.form.formfield');

class JFormFieldPhocaTextArea extends JFormField
{
	protected $type 		= 'PhocaTextArea';
	protected $phocaParams 	= null;

	protected function getInput() {
	
		$document		= JFactory::getDocument();
		$option 		= JRequest::getCmd('option');
		$globalValue 	= $this->_getPhocaParams( $this->element['name'] );
		
		// Initialize some field attributes.
		$class		= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';
		$disabled	= ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
		$columns	= $this->element['cols'] ? ' cols="'.(int) $this->element['cols'].'"' : '';
		$rows		= $this->element['rows'] ? ' rows="'.(int) $this->element['rows'].'"' : '';

		// Initialize JavaScript field attributes.
		$onchange	= $this->element['onchange'] ? ' onchange="'.(string) $this->element['onchange'].'"' : '';
		
		$value 		= htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8');
		
		// TODO 1.6
		// MENU - Set Default value to "" because of saving "" value into the menu link ( use global = "")
		if ($option == "com_menus") {
			$DefaultValue	= (string)$this->element['default'];
			if ($value == $DefaultValue) {
				$value = '';
			}
		}
		
		// MENU - Display the global value
		if ($option == "com_menus") {
			$html ='<table><tr><td colspan="3"><textarea name="'.$this->name.'" id="'.$this->id.'"' .
				$columns.$rows.$class.$disabled.$onchange.'>' .
				$value .
				'</textarea></td></tr>';		
			$html .='<tr><td>[</td><td><input  type="text"  value="'. $globalValue .'" style="width:15em;border:1px solid #fff;background:#fff;" /></td><td>]</td></tr></table>'; 
		} else {
			$html = '<textarea name="'.$this->name.'" id="'.$this->id.'"' .
				$columns.$rows.$class.$disabled.$onchange.'>' .
				$value .
				'</textarea>';
		}
		return $html;
	}
	
	protected function getLabel() {
		echo '<div class="clr"></div>';
		return parent::getLabel();
		echo '<div class="clr"></div>';
	}
	
	protected function _setPhocaParams(){
	
		$component 			= 'com_phocaguestbook';
		$table 				= JTable::getInstance('extension');
		$idCom				= $table->find( array('element' => $component ));
		$table->load($idCom);
		$phocaParams 		= new JParameter( $table->params );
		$this->phocaParams	= $phocaParams;
	}

	protected function _getPhocaParams( $name ){
	
		// Don't call sql query by every param item (it will be loaded only one time)
		if (!$this->phocaParams) {
			$params = $this->_setPhocaParams();
		}
		$globalValue 	= $this->phocaParams->get( $name, '' );	
		return $globalValue;
	}
}
?>
