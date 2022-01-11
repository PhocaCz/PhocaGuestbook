<?php
/**
 * @package    phocaguestbook
 * @subpackage Helpers
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Session\Session;
use Joomla\Registry\Registry;
require_once JPATH_COMPONENT.'/helpers/phocaguestbookonlinecheck.php';

class PhocaguestbookControllerPhocaguestbook extends FormController
{
	function cancel($key = NULL) {
		$uri 	= Uri::getInstance();
		$app    = Factory::getApplication();

		// Delete the data in the session.
		$app->setUserState('com_phocaguestbook.guestbook.data', '');

		// Redirect back to the guestbook form.
		$this->setRedirect(Route::_($uri));
		return false;
	}

	function submit() {

		$app    = Factory::getApplication();
		// Check for request forgeries.
		Session::checkToken() or jexit(Text::_('JINVALID_TOKEN'));
		$session = $app->getSession();

		// default session test always enabled!
		$valid = $session->get('form_id', NULL, 'phocaguestbook');
		$session->clear('form_id', 'phocaguestbook');
		if (!$valid){
			jexit(Text::_('COM_PHOCAGUESTBOOK_POSSIBLE_SPAM_DETECTED'));
		}

		$data  = $this->input->post->get('jform', array(), 'array');
		$id     = $this->input->getInt('cid');
		$model  = $this->getModel('guestbook');

		$uri 	= Uri::getInstance();
		$user 	= Factory::getUser();

		$model->setState('category.id', $id);


		// lets start processing



		$guestbook = $model->getGuestbook();
		if (!$guestbook) {

			throw new Exception(Text::_("COM_PHOCAGUESTBOOK_POSSIBLE_SPAM_DETECTED"), 500);
			return false;
		}

		// Load Logging
		$logging = $model->getTable('phocaguestbookLogging', 'Table');
		$logging->catid = $id;
		$logging->incoming_page = htmlspecialchars(Uri::getInstance()->toString());

		// Load the parameters.
		// Merge Global => GUESTBOOK => Menu Item params into new object in view
		$applparams = $app->getParams();
		$bookparams = new Registry;
		$menuParams = new Registry;
		$bookparams->loadString($guestbook->get('params'));
		if ($menu = $app->getMenu()->getActive()) {
			$menuParams->loadString($menu->getParams());
		}
		$params = clone $applparams;
		$params->merge($bookparams);
		$params->merge($menuParams);

		$namespace  = 'pgb' . $params->get('session_suffix', '');

		$captcha_id = $session->get('captcha_id',   null, $namespace);
		$params->set('captcha_id', $captcha_id );
		// Captcha not for registered
		if ($params->get('enable_captcha_users', 0) == 1 && $user->id > 0) {
			$params->set('enable_captcha', 0);
		}
		$logging->captchaid = $captcha_id;

		// Save params
		$model->setState('params', $params);

		// Get the data from POST
		$data['published'] = 1;
		$data['catid'] = $id;
		$data['userid'] = $user->id;

		if (!isset($data['website'])) {
			$data['website'] = '';
		}

		if (!isset($data['email'])) {
			$data['email'] = '';
		}


		//ipaddr
		if(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] != getenv('SERVER_ADDR')) {
			$data['userip']  = $_SERVER['REMOTE_ADDR'];
		} else {
			$data['userip']  = getenv('HTTP_X_FORWARDED_FOR');
		}

		if (!$data['userip']) {
			$data['userip'] = $_SERVER['REMOTE_ADDR'];
		}
		$logging->ip = $data['userip'];

		//captcha
		switch ($params->get('captcha_id')) {
			case 1: //COM_PHOCAGUESTBOOK_JOOMLA_CAPTCHA -> use diffent fields
			case 5: //COM_PHOCAGUESTBOOK_RECAPTCHA_CAPTCHA -> use diffent fields
			case 9:
				$data['captcha'] = 'dummy';
				break;
		}

		// Hidden Field check
		if ($params->get('enable_hidden_field', 0) 	== 1) {
			$params->set('hidden_field_id', $session->get('hidden_field_id', 'fieldnotvalid', $namespace));
			$params->set('hidden_field_name', $session->get('hidden_field_name', 'fieldnotvalid', $namespace));

			$session->clear('hidden_field_id', $namespace);
			$session->clear('hidden_field_name', $namespace);
			$session->clear('hidden_field_class', $namespace);

			if ($params->get('hidden_field_id') == 'fieldnotvalid') {
				$logging->hidden_field = 2;
				$model->doLog($logging,false);
				//no session id available
				throw new Exception(Text::_("COM_PHOCAGUESTBOOK_POSSIBLE_SPAM_DETECTED"), 500);
				return false;
			}
			$logging->hidden_field = 1;
		}

		// Check for a valid session cookie
		if($session->getState() != 'active'){
			// Save the data in the session.
			$app->setUserState('com_phocaguestbook.guestbook.data', $data);
			$logging->session = 2;
			$model->doLog($logging,false);

			throw new Exception(Text::_('COM_PHOCAGUESTBOOK_SESSION_INVALID'), 403);
			// Redirect back to the contact form.
			$this->setRedirect(Route::_($uri));
			return false;
		}

		// Security
		$task = $this->input->get('task');

		if ($task == 'phocaguestbook.submit') {
			$task = 'submit';
		}
		if (($this->input->get('view') != 'guestbook') || ($this->input->get('option') != 'com_phocaguestbook') || ($task != 'submit')) {
			$app->setUserState('com_phocaguestbook.guestbook.data', '');
			$session->clear('time', 'pgb'.$params->get('session_suffix', ''));

			$logging->session = 3;
			$model->doLog($logging,false);

			throw new Exception(Text::_("COM_PHOCAGUESTBOOK_POSSIBLE_SPAM_DETECTED"), 500);
			return false;
		}

		//Check if we are authorized to post to the guestbook
		if(!$params->get('access-post')) {
			$logging->session = 4;
			$model->doLog($logging,false);

			$app->redirect('index.php?option=com_users&view=login&return=' . base64_encode($uri), Text::_('COM_PHOCAGUESTBOOK_NOT_AUTHORIZED_DO_ACTION') . '. ');
			return;
		}
		$logging->session = 1;

		//Check Time
	    if($params->get('enable_time_check', 0) || $params->get('enable_logging', 0)) {
            $time = $session->get('time', null, 'pgb'.$params->get('session_suffix', ''));
            $delta = time() - $time;
			$logging->used_time = $delta;

			if($params->get('enable_time_check', 0) && $delta <= $params->get('time_check_s', 10))
            {

				throw new Exception(Text::_('COM_PHOCAGUESTBOOK_SUBMIT_TOO_FAST'), 403);
				// Save the data in the session.
				$app->setUserState('com_phocaguestbook.guestbook.data', $data);
				$model->doLog($logging,false);

				// Redirect back to the contact form.
				$this->setRedirect(Route::_($uri));
				return false;
            }
        }

		// IP BAN Check
		if ($params->get('form_action_banned_ip') != 2) {

			$isSpam = PhocaguestbookOnlineCheckHelper::checkIpAddress($data['userip'], $params, $logging);

			if ($isSpam) {
				if ($params->get('form_action_banned_ip', 0) == 1){
					$data['published'] = 0;
					//break;
				} else {
					$session->clear('time', 'pgb'.$params->get('session_suffix', ''));
					$model->doLog($logging,false);

					/*$app->setUserState('com_phocaguestbook.guestbook.data', '');
					throw new Exception(Text::_("COM_PHOCAGUESTBOOK_POSSIBLE_SPAM_DETECTED"), 500);
					return false;*/
					$app->setUserState('com_phocaguestbook.guestbook.data', $data);	// Save the data in the session.
					$app->enqueueMessage(Text::_( 'COM_PHOCAGUESTBOOK_PHOCA_GUESTBOOK_SPAM_BLOCKED' ), 'error');

					// Redirect back to the guestbook form.
					$this->setRedirect(Route::_($uri));
					return false;
				}
			}
		} //end of ip check

		$continueValidate = true;	//validate all fields
		$logging->fields = 1;

		// Validate the posted data.
		$form = $model->getForm();
		if (!$form) {
			$app->setUserState('com_phocaguestbook.guestbook.data', $data);	// Save the data in the session.
			$logging->fields = 10;
			$model->doLog($logging,false);

			throw new Exception($model->getError(), 500);
			return false;
		}


		$validate = $model->validate($form, $data);

		if ($validate === false) {
			$errors			= $model->getErrors();
			// Get (possible) attack issues
			for ($i = 0, $n = count($errors); $i < $n && $i < 5; $i++) {
				if (($errors[$i] instanceof JException) && ($errors[$i]->get('Level') == E_ERROR)) {
					throw new Exception(Text::_("COM_PHOCAGUESTBOOK_POSSIBLE_SPAM_DETECTED"), 500);

					$logging->fields = 11;
					$model->doLog($logging,false);
					return false;
				} else {
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
					$continueValidate = false;
					$logging->fields = 2;
				}
			}
		}



		//check url and hidden word
		$ffa	= explode( ',', trim( $params->get('deny_url_words', '') ) );
		$fwfa	= explode( ',', trim( $params->get('forbidden_word_filter', '') ) );
		$fwwfa	= explode( ',', trim( $params->get('forbidden_whole_word_filter', '') ) );
		$logging->forbidden_word = 1;

		//FORBIDDEN URL identication
		foreach ($ffa as $word) {
			if ($word != '') {
				if ((strpos($data['content'], $word) !== false)  ||
					(strpos($data['title'], $word) !== false) ||
					(strpos($data['username'], $word) !== false)) {

					$logging->forbidden_word = 2;
					switch ($params->get('form_action_denied_url', 0)){
						case 0: default://throw error
							$continueValidate = false;
							$app->enqueueMessage(Text::_('COM_PHOCAGUESTBOOK_DENY_URL' ), 'warning');
							break;
						case 1: // save item, but do not publish
							$data['published'] = 0;
							break;
						case 2: // save item - ignore error
							break;
					}
				}
			}
		}

		//FORBIDDEN WORD
		foreach ($fwfa as $item) {
			if (trim($item) != '') {
				switch ($params->get('form_action_hidden_word', 2)){
					case 0: default://throw error
						if (stripos($data['content'], trim($item)) !== false) {
							$continueValidate = false;
							$logging->forbidden_word = 4;
							$app->enqueueMessage(Text::_('COM_PHOCAGUESTBOOK_BAD_CONTENT' ), 'warning');
						}
						if (stripos($data['username'], trim($item)) !== false) {
							$continueValidate = false;
							$logging->forbidden_word = 4;
							$app->enqueueMessage(Text::_('COM_PHOCAGUESTBOOK_BAD_USERNAME' ), 'warning');
						}
						if (stripos($data['title'], trim($item)) !== false) {
							$continueValidate = false;
							$logging->forbidden_word = 4;
							$app->enqueueMessage(Text::_('COM_PHOCAGUESTBOOK_BAD_SUBJECT' ), 'warning');
						}
						if (stripos($data['email'], trim($item)) !== false) {
							$continueValidate = false;
							$logging->forbidden_word = 4;
							$app->enqueueMessage(Text::_('COM_PHOCAGUESTBOOK_BAD_EMAIL' ), 'warning');
						}
						if (stripos($data['website'], trim($item)) !== false) {
							$continueValidate = false;
							$logging->forbidden_word = 4;
							$app->enqueueMessage(Text::_('COM_PHOCAGUESTBOOK_BAD_WEBSITE' ), 'warning');
						}
						break;
					case 1: // save item, but do not publish
						if ((stripos($data['content'], trim($item)) !== false) ||
							(stripos($data['username'], trim($item)) !== false) ||
							(stripos($data['title'], trim($item)) !== false) ||
							(stripos($data['email'], trim($item)) !== false) ||
							(stripos($data['website'], trim($item)) !== false)) {
							$logging->forbidden_word = 4;
							$data['published'] = 0;
						}
						break;
					case 2: // save item - ignore error
						break;
				}
			}
		}
		foreach ($fwwfa as $item) {
			if ($item != '') {
				$item			= "/(^|[^a-zA-Z0-9_]){1}(".preg_quote(($item),"/").")($|[^a-zA-Z0-9_]){1}/i";

				switch ($params->get('form_action_hidden_word', 2)){
					case 0: default://throw error
						if (preg_match( $item, $data['content']) == 1) {
							$continueValidate = false;
							$logging->forbidden_word = 8;
							$app->enqueueMessage(Text::_('COM_PHOCAGUESTBOOK_BAD_CONTENT' ), 'warning');
						}
						if (preg_match( $item, $data['username']) == 1) {
							$continueValidate = false;
							$logging->forbidden_word = 8;
							$app->enqueueMessage(Text::_('COM_PHOCAGUESTBOOK_BAD_USERNAME' ), 'warning');
						}
						if (preg_match( $item, $data['title']) == 1) {
							$continueValidate = false;
							$logging->forbidden_word = 8;
							$app->enqueueMessage(Text::_('COM_PHOCAGUESTBOOK_BAD_SUBJECT' ), 'warning');
						}
						if (preg_match( $item, $data['email']) == 1) {
							$continueValidate = false;
							$logging->forbidden_word = 8;
							$app->enqueueMessage(Text::_('COM_PHOCAGUESTBOOK_BAD_EMAIL' ), 'warning');
						}
						if (preg_match( $item, $data['website']) == 1) {
							$continueValidate = false;
							$logging->forbidden_word = 8;
							$app->enqueueMessage(Text::_('COM_PHOCAGUESTBOOK_BAD_WEBSITE' ), 'warning');
						}
						break;
					case 1: // save item, but do not publish
						if ((preg_match( $item, $data['content']) == 1) ||
							(preg_match( $item, $data['username']) == 1) ||
							(preg_match( $item, $data['title']) == 1) ||
							(preg_match( $item, $data['email']) == 1) ||
							(preg_match( $item, $data['website']) == 1)) {
							$logging->forbidden_word = 8;
							$data['published'] = 0;
						}
						break;
					case 2: // save item - ignore error
						break;
				}
			}
		}

		//remove captcha from data after check
		$data['captcha'] = '';
		if ($continueValidate == false) {
			// Save the data in the session.
			$app->setUserState('com_phocaguestbook.guestbook.data', $data);
			$model->doLog($logging,false);

			// Redirect back to the guestbook form.
			$this->setRedirect(Route::_($uri));
			return false;
		}

		//Check  spam:
		// Akismet,        see http://akismet.com/
		// Mollom,         see http://mollom.com/
		// StopforumSpam,  see http://www.stopforumspam.com/
		// Honeypot,       see http://www.projecthoneypot.org/
		// Botscout,       see http://botscout.com/
		if ($params->get('contentcheck_block_spam', 0) != 2){
			$suspectSpam = PhocaguestbookOnlineCheckHelper::checkSpam($data, $params, $logging);  //print_r($feedback);
			if ($suspectSpam){
				if ($params->get('contentcheck_block_spam', 0) != 1){
					$model->doLog($logging,false);

					$app->setUserState('com_phocaguestbook.guestbook.data', $data);	// Save the data in the session.
					$app->enqueueMessage(Text::_( 'COM_PHOCAGUESTBOOK_PHOCA_GUESTBOOK_SPAM_BLOCKED' ), 'error');

					// Redirect back to the guestbook form.
					$this->setRedirect(Route::_($uri));
					return false;
				} else {
					$data['published'] = 0;
				}
			}
		}

		// CHECKS DONE - store entry

		$msg = '';
		if ($model->store($data)) {
			$logging->postid = $data['id'];

			if ($data['published'] == 0) {
				$logging->state = 2;
				$msg = Text::_( 'COM_PHOCAGUESTBOOK_SUCCESS_SAVE_ITEM' ). ", " .Text::_( 'COM_PHOCAGUESTBOOK_REVIEW_MESSAGE' );
			} else {
				$logging->state = 1;
				$msg = Text::_( 'COM_PHOCAGUESTBOOK_SUCCESS_SAVE_ITEM' );
			}


			$model->doLog($logging,true);
		} else {
			$model->doLog($logging,false);
		}

		// Flush the data from the session
		$session->clear('time', 'pgb'.$params->get('session_suffix', ''));
		$app->setUserState('com_phocaguestbook.guestbook.data', null);
		$app->enqueueMessage($msg, 'success');
		$this->setRedirect($uri->toString());
		return true;

	}

	function delete() {
		$app    = Factory::getApplication();
		$model  = $this->getModel('guestbook');
		$id     = $this->input->getInt('cid');
		$model->setState('category.id', $id);

		// Load the parameters.
		$guestbook = $model->getGuestbook();
		if (!$guestbook) {
			throw new Exception(Text::_("COM_PHOCAGUESTBOOK_POSSIBLE_SPAM_DETECTED"), 500);
			return false;
		}
		$bookparams = new Registry;
		$bookparams->loadString($guestbook->get('params'));
		$model->setState('params', $bookparams);

		$cid 		= $this->input->getInt( 'mid', '');
		$itemid 	= $this->input->getInt( 'Itemid', '');
		$limitstart	= $this->input->getInt( 'start', '');

		if ($bookparams->get('access-delete')) {

			if (count( $cid ) < 1) {

				throw new Exception(Text::_( 'COM_PHOCAGUESTBOOK_WARNING_SELECT_ITEM_DELETE' ), 500);
				return false;
			}
			if(!$model->delete($cid)) {
				$app->enqueueMessage(Text::_( 'COM_PHOCAGUESTBOOK_ERROR_DELETE_ITEM' ));
			} else {
				$app->enqueueMessage(Text::_( 'COM_PHOCAGUESTBOOK_SUCCESS_DELETE_ITEM'), 'success');
			}
		} else {
			$app->enqueueMessage(Text::_( 'COM_PHOCAGUESTBOOK_NOT_AUTHORIZED_DO_ACTION' ));
		}

		// Redirect
		$link	= 'index.php?option=com_phocaguestbook&task=phocaguestbook.guestbook&id='.$id.'&Itemid='.$itemid.'&start='.$limitstart;
		$link	= Route::_($link, false);
		$this->setRedirect( $link );
	}

	function unpublish() {
		$this->changeState(0);
	}

	function publish() {
		$this->changeState(1);
	}

	function changeState($newState) {
		$app	= Factory::getApplication();
		$model	= $this->getModel('guestbook');
		$catid	= $this->input->getInt('cid');
		$model->setState('category.id', $catid);

		// Load the parameters.
		$guestbook = $model->getGuestbook();
		if (!$guestbook) {
			throw new Exception(Text::_("COM_PHOCAGUESTBOOK_POSSIBLE_SPAM_DETECTED"), 500);
			return false;
		}

		$bookparams = new Registry;
		$bookparams->loadString($guestbook->get('params'));
		$model->setState('params', $bookparams);

		$entryid 	= $this->input->getInt( 'mid', '');
		$itemid 	= $this->input->getInt( 'Itemid', '');
		$limitstart	= $this->input->getInt( 'start', '');

		if ($bookparams->get('access-state')) {
			if (count( $entryid ) < 1) {

				throw new Exception(Text::_( 'COM_PHOCAGUESTBOOK_WARNING_SELECT_ITEM_UNPUBLISH' ), 500);
				return false;
			}
			if(!$model->publish($entryid, $catid, $newState)) {
				$app->enqueueMessage($newState ? Text::_( 'COM_PHOCAGUESTBOOK_ERROR_PUBLISH_ITEM' ) : Text::_( 'COM_PHOCAGUESTBOOK_ERROR_UNPUBLISH_ITEM' ));
			}
			else {
				$app->enqueueMessage($newState ? Text::_( 'COM_PHOCAGUESTBOOK_SUCCESS_PUBLISH_ITEM') : Text::_( 'COM_PHOCAGUESTBOOK_SUCCESS_UNPUBLISH_ITEM'), 'success');
			}
		} else {
			$app->enqueueMessage(Text::_( 'COM_PHOCAGUESTBOOK_NOT_AUTHORIZED_DO_ACTION' ));
		}

		// Redirect
		$link	= 'index.php?option=com_phocaguestbook&task=phocaguestbook.guestbook&id='.$id.'&Itemid='.$itemid.'&start='.$limitstart;
		$link	= Route::_($link, false);
		$this->setRedirect( $link);
	}

}
?>
