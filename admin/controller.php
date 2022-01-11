<?php
/**
 * @package    phocaguestbook
 * @subpackage Base
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
//-- No direct access
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Factory;

/**
 * phocaguestbook default Controller.
 *
 * @package    phocaguestbook
 * @subpackage Controllers
 */
class PhocaguestbookController extends BaseController
{
	protected $default_view = 'phocaguestbookcp';

    /**
     * Method to display the view.
     *
     * @param bool $cachable
     * @param bool $urlparams
     *
     * @return void
     */
    public function display($cachable = false, $urlparams = false)
    {
		$view	= Factory::getApplication()->input->get('view');
		$layout	= Factory::getApplication()->input->get('layout');
		$id     = Factory::getApplication()->input->getInt('id');

		phocaguestbookHelper::addSubmenu($view);
        parent::display($cachable, $urlparams);

        return $this;
    }
}
