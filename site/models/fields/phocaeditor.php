<?php
/**
 * @package     Joomla.Libraries
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

class JFormFieldPhocaEditor extends JFormField
{

	public $type = 'PhocaEditor';
	protected $editor;
	
	protected function getInput(){
	
		$app = JFactory::getApplication();
		$class = $this->element['class'] ? ' class="' . (string) $this->element['class'] . ' mceEditor mce_editable"' : 'mce_editable';
		
		$disabled = ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
		$columns = $this->element['cols'] ? ' cols="' . (int) $this->element['cols'] . '"' : '';
		$rows = $this->element['rows'] ? ' rows="' . (int) $this->element['rows'] . '"' : '';

		// Initialize JavaScript field attributes.
		$onchange = $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';
		
		$height      = ((string) $this->element['height']) ? (string) $this->element['height'] : '250';
		$width       = ((string) $this->element['width']) ? (string) $this->element['width'] : '100%';
		$assetField  = $this->element['asset_field'] ? (string) $this->element['asset_field'] : 'asset_id';
		$authorField = $this->element['created_by_field'] ? (string) $this->element['created_by_field'] : 'created_by';
		$asset       = $this->form->getValue($assetField) ? $this->form->getValue($assetField) : (string) $this->element['asset_id'];

		// Build the buttons array.
		$buttons = (string) $this->element['buttons'];

		if ($buttons == 'true' || $buttons == 'yes' || $buttons == '1')
		{
			$buttons = true;
		}
		elseif ($buttons == 'false' || $buttons == 'no' || $buttons == '0')
		{
			$buttons = false;
		}
		else
		{
			$buttons = explode(',', $buttons);
		}

		$hide = ((string) $this->element['hide']) ? explode(',', (string) $this->element['hide']) : array();
		
		
		
		
		
		// We search for defined editor (tinymce)
		$editor = $this->getEditor();
		if ($editor) {
			
			// EDITOR PARAMS
			$plugin 	= JPluginHelper::getPlugin('editors', 'tinymce');
			$paramsE 	= new JRegistry($plugin->params);
			$language 		 = JFactory::getLanguage();
			
			$user     = JFactory::getUser();
			$language = JFactory::getLanguage();
			$theme    = 'modern';
			$ugroups  = array_combine($user->getAuthorisedGroups(), $user->getAuthorisedGroups());

			// Prepare the parameters
			$levelParams      = new Joomla\Registry\Registry;
			$extraOptions     = new stdClass;
			$toolbarParams    = new stdClass;
			
			$configuration	= $paramsE->get('configuration');
			
			if (!empty($configuration)) {
				$extraOptionsAll  = $configuration->setoptions;
				$toolbarParamsAll = $configuration->toolbars;

				// Get configuration depend from User group
				if (!empty($extraOptionsAll)) {
					foreach ($extraOptionsAll as $set => $val)
					{
						$val->access = empty($val->access) ? array() : $val->access;

						// Check whether User in one of allowed group
						foreach ($val->access as $group)
						{
							if (isset($ugroups[$group]))
							{
								$extraOptions  = $val;
								$toolbarParams = $toolbarParamsAll->$set;
							}
						}
					}
				}
			}
			// Merge the params
			$levelParams->loadObject($toolbarParams);
			$levelParams->loadObject($extraOptions);

			// List the skins
			$skindirs = glob(JPATH_ROOT . '/media/editors/tinymce/skins' . '/*', GLOB_ONLYDIR);

			// Set the selected skin
			$skin = 'lightgray';
			$side = $app->isClient('administrator') ? 'skin_admin' : 'skin';

			if ((int) $levelParams->get($side, 0) < count($skindirs))
			{
				$skin = basename($skindirs[(int) $levelParams->get($side, 0)]);
			}

			$langMode   = $levelParams->get('lang_mode', 1);
			$langPrefix = $levelParams->get('lang_code', 'en');
			
			
			if ($langMode)
			{
				if (file_exists(JPATH_ROOT . "/media/editors/tinymce/langs/" . $language->getTag() . ".js"))
				{
					$langPrefix = $language->getTag();
				}
				elseif (file_exists(JPATH_ROOT . "/media/editors/tinymce/langs/" . substr($language->getTag(), 0, strpos($language->getTag(), '-')) . ".js"))
				{
					$langPrefix = substr($language->getTag(), 0, strpos($language->getTag(), '-'));
				}
				else
				{
					$langPrefix = "en";
				}
			}

			$text_direction = 'ltr';

			if ($language->isRTL())
			{
				$text_direction = 'rtl';
			}
		
		
		
			$js =	'<script type="text/javascript">' . "\n";
			$js .= 	 'tinyMCE.init({'. "\n"
						.'mode : "textareas",'. "\n"
						.'theme : "advanced",'. "\n"
						.'directionality: "'.$text_direction.'",'. "\n"
						.'language : "'.$langPrefix.'",'. "\n"
						.'plugins : "emotions",'. "\n"
						.'editor_selector : "mceEditor",'. "\n"					
						.'theme_advanced_buttons1 : "bold, italic, underline, separator, strikethrough, justifyleft, justifycenter, justifyright, justifyfull, bullist, numlist, undo, redo, link, unlink, separator, emotions",'. "\n"
						.'theme_advanced_buttons2 : "",'. "\n"
						.'theme_advanced_buttons3 : "",'. "\n"
						.'theme_advanced_toolbar_location : "top",'. "\n"
						.'theme_advanced_toolbar_align : "left",'. "\n";
			//if ($displayPath == 1) {
				$js .= 'theme_advanced_path_location : "bottom",'. "\n";
			//}
			$js .=		 'extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]"
	});' . "\n";
			$js .=	'</script>';
			
			$js2 = "\t<script type=\"text/javascript\" src=\"".JURI::root()."media/editors/tinymce/jscripts/tiny_mce/tiny_mce.js\"></script>\n";
			
			
			$js = '<script type="text/javascript">
				tinyMCE.init({
					// General
					directionality: "'.$text_direction.'",
					language : "'.$langPrefix.'",
					menubar:false,
					statusbar: false,
					mode : "specific_textareas",
					skin : "lightgray",
					theme : "modern",
					schema: "html5",
					selector: "textarea.mce_editable",
					// Cleanup/Output
					inline_styles : true,
					gecko_spellcheck : true,
					entity_encoding : "raw",
					extended_valid_elements : "hr[id|title|alt|class|width|size|noshade]",
					force_br_newlines : false, force_p_newlines : true, forced_root_block : \'p\',
					toolbar_items_size: "small",
					invalid_elements : "script,applet,iframe",
					// Plugins
					plugins : "link image autolink lists emoticons",
					// Toolbar
					toolbar1: "bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist | undo redo | link unlink anchor image emoticons",
					removed_menuitems: "newdocument",
					// URL
					relative_urls : true,
					remove_script_host : false,
					document_base_url : "'.JURI::base().'",
					// Layout
					content_css : "'.JURI::base().'templates/system/css/editor.css",
					//importcss_append: true,
					// Advanced Options
					resize: "both",
					//height : "550",
					//width : "750",

				});
				</script>';
			
			$js2 = "\t<script type=\"text/javascript\" src=\"".JURI::root()."media/editors/tinymce/tinymce.min.js\"></script>\n";
			
			
			$document	= JFactory::getDocument();
			$document->addCustomTag($js2);
			$document->addCustomTag($js);
			
			if (is_numeric( $width )) {
				$width .= 'px';
			}
			if (is_numeric( $height )) {
				$height .= 'px';
			}
			
			// Problem with required
			$class = str_replace('required', '', $class);
			
			$editor = '<textarea  name="' . $this->name . '" id="' . $this->id . '"' . $columns . $rows . $class . $disabled . $onchange . ' style="width:' . $width .'; height:'. $height.'">'
				. htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '</textarea>';
		} else {
			$editor = '<textarea  name="' . $this->name . '" id="' . $this->id . '"' . $columns . $rows . $class . $disabled . $onchange . ' style="width:' . $width .'; height:'. $height.'">'
				. htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '</textarea>';
		}
		return $editor;
	}

	/**
	 * Method to get a JEditor object based on the form field.
	 *
	 * @return  JEditor  The JEditor object.
	 *
	 * @since   1.6
	 */
	protected function getEditor()
	{
		// Only create the editor if it is not already created.
		if (empty($this->editor))
		{
			$editor = null;

			// Get the editor type attribute. Can be in the form of: editor="desired|alternative".
			$type = trim((string) $this->element['editor']);

			if ($type)
			{
				// Get the list of editor types.
				$types = explode('|', $type);

				// Get the database object.
				$db = JFactory::getDBO();

				// Iterate over teh types looking for an existing editor.
				foreach ($types as $element)
				{
					// Build the query.
					$query = $db->getQuery(true);
					$query->select('element');
					$query->from('#__extensions');
					$query->where('element = ' . $db->quote($element));
					$query->where('folder = ' . $db->quote('editors'));
					$query->where('enabled = 1');

					// Check of the editor exists.
					$db->setQuery($query, 0, 1);
					$editor = $db->loadResult();

					// If an editor was found stop looking.
					if ($editor)
					{
						break;
					}
				}
			}

			// Create the JEditor instance based on the given editor.
			if (is_null($editor))
			{
				$conf = JFactory::getConfig();
				$editor = $conf->get('editor');
			}
			//PHOCAEDIT
			if ($editor != trim((string) $this->element['editor'])) {
				return false;
			}
			// END PHOCAEDIT
			
			$this->editor = JEditor::getInstance($editor);
		
		}

		return $this->editor;
	}

	public function save()
	{
		return $this->getEditor()->save($this->id);
	}
}
