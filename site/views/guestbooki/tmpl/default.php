<?php 
/**
 * @package    phocaguestbook
 * @subpackage Views
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
	// no direct access
	defined('_JEXEC') or die('Restricted access');
	header("Expires: Sun, 1 Jan 2000 12:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
	header("Cache-Control: no-store, no-cache, must-revalidate"); 
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	header('Content-type: image/jpeg');
	imagejpeg($this->image,NULL,100);
	ImageDestroy($this->image);exit;
?>
