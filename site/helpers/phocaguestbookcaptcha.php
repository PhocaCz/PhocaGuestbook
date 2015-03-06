<?php
/**
 * @package    phocaguestbook
 * @subpackage Helpers
 * @copyright  Copyright (C) 2012 Jan Pavelka www.phoca.cz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

class PhocaguestbookHelperCaptchaTTF
{
	function createImageData()
	{
		$rand_char 			= PhocaguestbookHelperCaptchaTTF::generateRandomChar(6);
		$image_name 		= PhocaguestbookHelperCaptchaTTF::getRandomImage();
		$image 				= @imagecreatefromjpeg($image_name);
		
		$ttf[0]				= JPATH_COMPONENT . '/assets/captcha/fonts/essai.ttf';
	//	$ttf[1]				= JPATH_COMPONENT . '/assets/captcha/fonts/vera.ttf';
		$ttf[1]				= JPATH_COMPONENT . '/assets/captcha/fonts/justus.ttf';
		
		$i = 15;
		$char_string = '';
		foreach ($rand_char as $key => $value)
		{
			$font_color 	= PhocaguestbookHelperCaptchaTTF::getRandomFontColor();
			$position_x 	= PhocaguestbookHelperCaptchaTTF::getRandomPositionX($i);
			$position_y 	= mt_rand(55,80);
			$font_size 		= mt_rand(20,40);
			$angle			= mt_rand(-30,30);
			$rand_ttf		= mt_rand(0,1);
			
			imagettftext($image, $font_size, $angle, $position_x, $position_y, ImageColorAllocate ($image, $font_color[0], $font_color[1], $font_color[2]), $ttf[$rand_ttf], $value);
			$i = $i + 37;
			$char_string .= $value;
		}

		$image_data['outcome'] 		= $char_string;//$rand_char;
		$image_data['image'] 		= $image;
		
		return $image_data;
	}
	
	function generateRandomChar($length=6)
	{	
	
		$paramsC 	= JComponentHelper::getParams('com_phocaguestbook') ;
		
		$charGroup = 'a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z';
		if ($paramsC->get( 'ttf_captcha_chars' ) != '') {
			$charGroup = str_replace(" ", "", trim( $paramsC->get( 'ttf_captcha_chars' ) ));
		}
		$charGroup = explode( ',', $charGroup );
	
		
		srand ((double) microtime() * 1000000);
		
		$random_array = array();
		
		for($i=0;$i<$length;$i++)
		{
			$random_char_group = rand(0,sizeof($charGroup)-1);
			
			$random_array[]	= $charGroup[$random_char_group];
		}
		return $random_array;
	}

	function getRandomImage() {
		$rand 	= mt_rand(5,8);
		$image 	= '0'.$rand.'.jpg';
		$image 	= JPATH_ROOT.'/components/com_phocaguestbook/assets/captcha/'. $image;
		return $image;
	}

	function getRandomPositionX($i) {
		$rand_2 = mt_rand(-2,3);
		$rand_3 = $i + ($rand_2);
		
		if ((int)$i > $rand_3) {
			$rand 	= mt_rand($rand_3, $i);
		} else {
			$rand 	= mt_rand($i,$rand_3);
		}
		return $rand;
	}	

	function getRandomFontColor() {
		$rand = mt_rand(1,6);
		if ($rand == 1) {$font_color[0] = 0; $font_color[1] = 20; $font_color[2] = 0;}
		if ($rand == 2) {$font_color[0] = 0; $font_color[1] = 0; $font_color[2] = 143;}
		if ($rand == 3) {$font_color[0] = 10; $font_color[1] = 102; $font_color[2] = 0;}
		if ($rand == 4) {$font_color[0] = 110; $font_color[1] = 58; $font_color[2] = 0;}
		if ($rand == 5) {$font_color[0] = 170; $font_color[1] = 0; $font_color[2] = 0;}
		if ($rand == 6) {$font_color[0] = 0; $font_color[1] = 93; $font_color[2] = 174;}
		return $font_color;
	}
}


// ================================================


class PhocaguestbookHelperCaptchaMath
{	
	function createImageItem($item)
	{
		switch ($item)
		{
			// 1 ---------------------------------------------------------------------
			case 1:
			$randChar['char'] 	= PhocaguestbookHelperCaptchaMath::generateRandomChar(10);
			$ch					= $randChar['char'];
			$randChar['array'] 	= array (	
"   ".$ch[0]." ",
"  ".$ch[1].$ch[2]." ",
" ".$ch[3]." ".$ch[4]." ",
$ch[5]."  ".$ch[6]." ",
"   ".$ch[7]." ",
"   ".$ch[8]." ",
"   ".$ch[9]." ",
);
			break;
			
			// 2 ---------------------------------------------------------------------
			case 2:
			$randChar['char'] 	= PhocaguestbookHelperCaptchaMath::generateRandomChar(14);
			$ch					= $randChar['char'];
			$randChar['array'] 	= array (	
" ".$ch[0].$ch[1].$ch[2]." ",
$ch[3]."   ".$ch[4],
"   ".$ch[5]." ",
"  ".$ch[6]."  ",
" ".$ch[7]."   ",
$ch[8]."    ",
$ch[9].$ch[10].$ch[11].$ch[12].$ch[13]
);
			break;
			
			// 3 ---------------------------------------------------------------------
			case 3:
			$randChar['char'] 	= PhocaguestbookHelperCaptchaMath::generateRandomChar(14);
			$ch					= $randChar['char'];
			$randChar['array'] 	= array (		
" ".$ch[0].$ch[1].$ch[2]." ",
$ch[3]."   ".$ch[4],
"    ".$ch[5],
"  ".$ch[6].$ch[7]." ",
"    ".$ch[8],
$ch[9]."   ".$ch[10],
" ".$ch[11].$ch[12].$ch[13]." "
);
			break;
			
			// 4 ---------------------------------------------------------------------
			case 4:
			$randChar['char'] 	= PhocaguestbookHelperCaptchaMath::generateRandomChar(12);
			$ch					= $randChar['char'];
			$randChar['array'] 	= array (		
"   ".$ch[0]." ",
"  ".$ch[1]."  ",
" ".$ch[2]."   ",
$ch[3]."  ".$ch[4]." ",
$ch[5].$ch[6].$ch[7].$ch[8].$ch[9],
"   ".$ch[10]." ",
"   ".$ch[11]." "
);
			break;
			
			// 5 ---------------------------------------------------------------------
			case 5:
			$randChar['char'] 	= PhocaguestbookHelperCaptchaMath::generateRandomChar(17);
			$ch					= $randChar['char'];
			$randChar['array'] 	= array (
$ch[0].$ch[1].$ch[2].$ch[3].$ch[4],
$ch[5]."    ",
$ch[6].$ch[7].$ch[8].$ch[9]." ",
"    ".$ch[10],
"    ".$ch[11],
$ch[12]."   ".$ch[13],
" ".$ch[14].$ch[15].$ch[16]." "
);
			break;
			
			// 6 ---------------------------------------------------------------------
			case 6:
			$randChar['char'] 	= PhocaguestbookHelperCaptchaMath::generateRandomChar(16);
			$ch					= $randChar['char'];
			$randChar['array'] 	= array (
" ".$ch[0].$ch[1].$ch[2]." ",
$ch[3]."    ",
$ch[4]."    ",
$ch[5].$ch[6].$ch[7].$ch[8]." ",
$ch[9]."   ".$ch[10],
$ch[11]."   ".$ch[12],
" ".$ch[13].$ch[14].$ch[15]." "
);
			break;
			
			// 7 ---------------------------------------------------------------------
			case 7:
			$randChar['char'] 	= PhocaguestbookHelperCaptchaMath::generateRandomChar(11);
			$ch					= $randChar['char'];
			$randChar['array'] 	= array (
			
$ch[0].$ch[1].$ch[2].$ch[3].$ch[4],
"    ".$ch[5],
"    ".$ch[6],
"   ".$ch[7]." ",
"  ".$ch[8]."  ",
" ".$ch[9]."   ",
$ch[10]."    "
);
			break;
			
			// 8 ---------------------------------------------------------------------
			case 8:
			$randChar['char'] 	= PhocaguestbookHelperCaptchaMath::generateRandomChar(17);
			$ch					= $randChar['char'];
			$randChar['array'] 	= array (
			
" ".$ch[0].$ch[1].$ch[2]." ",
$ch[3]."   ".$ch[4],
$ch[5]."   ".$ch[6],
" ".$ch[7].$ch[8].$ch[9]." ",
$ch[10]."   ".$ch[11],
$ch[12]."   ".$ch[13],
" ".$ch[14].$ch[15].$ch[16]." "
);
			break;
			
			// 9 ---------------------------------------------------------------------
			case 9:
			$randChar['char'] 	= PhocaguestbookHelperCaptchaMath::generateRandomChar(16);
			$ch					= $randChar['char'];
			$randChar['array'] 	= array (	
" ".$ch[0].$ch[1].$ch[2]." ",
$ch[3]."   ".$ch[4],
$ch[5]."   ".$ch[6],
" ".$ch[7].$ch[8].$ch[9].$ch[10],
"    ".$ch[11],
"    ".$ch[12],
" ".$ch[13].$ch[14].$ch[15]." "
);
			break;
			
			// 10 + ------------------------------------------------------------------
			case 10:
			$randChar['char'] 	= PhocaguestbookHelperCaptchaMath::generateRandomChar(9);
			$ch					= $randChar['char'];
			$randChar['array'] 	= array (	
"     ",
"  ".$ch[0]."  ",
"  ".$ch[1]."  ",
$ch[2].$ch[3].$ch[4].$ch[5].$ch[6],
"  ".$ch[7]."  ",
"  ".$ch[8]."  ",
"     "
);
			break;
			
			// 11 - ------------------------------------------------------------------
			case 11:
			$randChar['char'] 	= PhocaguestbookHelperCaptchaMath::generateRandomChar(5);
			$ch					= $randChar['char'];
			$randChar['array'] 	= array (	
"     ",
"     ",
"     ",
$ch[0].$ch[1].$ch[2].$ch[3].$ch[4],
"     ",
"     ",
"     "
);
			break;
			
			// 12 x ------------------------------------------------------------------
			case 12:
			$randChar['char'] 	= PhocaguestbookHelperCaptchaMath::generateRandomChar(9);
			$ch					= $randChar['char'];
			$randChar['array'] 	= array (
"     ",			
$ch[0]."   ".$ch[1],
" ".$ch[2]." ".$ch[3]." ",
"  ".$ch[4]."  ",
" ".$ch[5]." ".$ch[6]." ",
$ch[7]."   ".$ch[8],
"     "
);	
			break;
			
			// 13 : ------------------------------------------------------------------
			case 13:
			$randChar['char'] 	= PhocaguestbookHelperCaptchaMath::generateRandomChar(8);
			$ch					= $randChar['char'];
			$randChar['array'] 	= array (
"     ",			
"  ".$ch[0].$ch[1]." ",
"  ".$ch[2].$ch[3]." ",
"     ",
"     ",
"  ".$ch[4].$ch[5]." ",
"  ".$ch[6].$ch[7]." "
);	
			break;
			
			// 15 = ------------------------------------------------------------------
			case 15:
			$randChar['char'] 	= PhocaguestbookHelperCaptchaMath::generateRandomChar(10);
			$ch					= $randChar['char'];
			$randChar['array'] 	= array (
			
"     ",
"     ",
$ch[0].$ch[1].$ch[2].$ch[3].$ch[4],
"     ",
$ch[5].$ch[6].$ch[7].$ch[8].$ch[9],
"     ",
"     "
);
			break;
		}
		return $randChar;
	}


	function createImageData()
	{
		$image_name 		= PhocaguestbookHelperCaptchaMath::getRandomImage();
		$image 				= @imagecreatefromjpeg($image_name);
			
		$math = PhocaguestbookHelperCaptchaMath::getMath();

		$items = array( 0 => $math['first'], 1 => $math['operation'], 2 => $math['second'], 3 => 15);
		
		$x = 18;//Position X (first)
		for ($i=0;$i<4;$i++)
		{		
			$randChar = PhocaguestbookHelperCaptchaMath::createImageItem($items[$i]);
			// Position Y (first) ---
			if ($i == 1) {
				$y = 20;
			} else {
				$y = 10;
			}
			// -----------------------
			foreach ($randChar['array'] as $key => $value)
			{
				$font_color 	= PhocaguestbookHelperCaptchaMath::getRandomFontColor();
				
				if ($i == 1 || $i == 3) {
					$font_size 	= 2;
				} else {
					$font_size	= 5;
				}
				
				$position_x 	= $x;
				$position_y		= $y;
				
				ImageString($image, $font_size, $position_x, $position_y, $value, ImageColorAllocate ($image, $font_color[0], $font_color[1], $font_color[2]));
				if ($i == 1) {
					$y = $y + 7;
				} else {
					$y = $y + 11;
				}
			}
			if ($i == 0 || $i == 2) {
				$x = $x + 70;
			} else {
				$x = $x + 50;
			}
		}
		// Here is not the rand char but the matematical outcome
		$image_data['outcome'] 		= $math['outcome'];
		$image_data['image'] 		= $image;
		
		return $image_data;
	}
	
	function generateRandomChar($length=6)
	{	
	
		$paramsC 	= JComponentHelper::getParams('com_phocaguestbook') ;
		
		$charGroup = 'a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z';
		if ($paramsC->get( 'math_captcha_chars' ) != '') {
			$charGroup = str_replace(" ", "", trim( $paramsC->get( 'math_captcha_chars' ) ));
		}
		$charGroup = explode( ',', $charGroup );
	
		
		srand ((double) microtime() * 1000000);
		
		$random_string = "";
		
		for($i=0;$i<$length;$i++)
		{
			$random_char_group = rand(0,sizeof($charGroup)-1);
			
			$random_string .= $charGroup[$random_char_group];
		}
		return $random_string;
	}

	function getRandomImage()
	{
		$rand = mt_rand(10,13);
		$image = ''.$rand.'.jpg';
		$image = JPATH_ROOT.'/components/com_phocaguestbook/assets/captcha/'. $image;
		return $image;
	}


	function getRandomFontColor()
	{
		$rand = mt_rand(1,6);
		if ($rand == 1) {$font_color[0] = 0; $font_color[1] = 0; $font_color[2] = 0;}
		if ($rand == 2) {$font_color[0] = 0; $font_color[1] = 0; $font_color[2] = 153;}
		if ($rand == 3) {$font_color[0] = 0; $font_color[1] = 102; $font_color[2] = 0;}
		if ($rand == 4) {$font_color[0] = 102; $font_color[1] = 51; $font_color[2] = 0;}
		if ($rand == 5) {$font_color[0] = 163; $font_color[1] = 0; $font_color[2] = 0;}
		if ($rand == 6) {$font_color[0] = 0; $font_color[1] = 82; $font_color[2] = 163;}
		return $font_color;
	}
	
	function getMath()
	{
		$math['first'] 		= mt_rand(1,9);
		$math['second']		= mt_rand(1,9);
		$math['operation']	= mt_rand(10,13);

		switch ($math['operation'])
		{
			case 10;
				$math['outcome']	=  (int)$math['first'] + (int)$math['second'];
			break;
			
			case 11;
				if ((int)$math['first'] < (int)$math['second']) {
					$prevFirst		= $math['first'];
					$math['first'] 	= $math['second'];
					$math['second'] = $prevFirst;
				}
				
				$outcome = (int)$math['first'] - (int)$math['second'];
				if ($outcome == 0) {
					$math['second'] = $math['second'] - 1;
				}
				$math['outcome']	=  (int)$math['first'] - (int)$math['second'];
			break;
			
			case 12;
				$math['outcome']	=  (int)$math['first'] * (int)$math['second'];
			break;
			
			case 13;
				switch ($math['first'])
				{
					case 9:
						$second	= array(1,3,9,9);
					break;
					case 8:
						$second	= array(1,2,4,8);
					break;
					case 7:
						$second	= array(1,7,7,7);
					break;
					case 6:
						$second	= array(1,2,3,6);
					break;
					case 5:
						$second	= array(1,5,5,5);
					break;
					case 4:
						$second	= array(1,2,4,4);
					break;
					case 3:
						$second	= array(1,3,3,3);
					break;
					case 2:
						$second	= array(1,2,2,2);
					break;
					case 1:
					default:
						$second	= array(1,1,1,1);
					break;
				}
				$randSecond = mt_rand(0,3);
				$math['outcome']	= (int)$math['first'] / (int)$second[$randSecond];
				$math['second']		= (int)$second[$randSecond];// We must define the second new
			break;
		}
		return $math;
	}
}


// ================================================
class PhocaguestbookHelperCaptchaStd
{
	function createImageData()
	{
		$rand_char 			 = PhocaguestbookHelperCaptchaStd::generateRandomChar(6);
		$rand_char_array 	 = array (			$rand_char[0]."          ",
										   "  ".$rand_char[1]."        "	,
										 "    ".$rand_char[2]."      "	,
									   "      ".$rand_char[3]."    "   ,
									 "        ".$rand_char[4]."  "	,
								   "          ".$rand_char[5]);

		$image_name 		= PhocaguestbookHelperCaptchaStd::getRandomImage();
		$image 				= @imagecreatefromjpeg($image_name);
		 
		foreach ($rand_char_array as $key => $value)
		{
			$font_color 	= PhocaguestbookHelperCaptchaStd::getRandomFontColor();
			$position_x 	= mt_rand(5,8);
			$position_y 	= mt_rand(6,9);
			$font_size 		= mt_rand(4,5);
			
			ImageString($image, $font_size, $position_x, $position_y, $value, ImageColorAllocate ($image, $font_color[0], $font_color[1], $font_color[2]));
		}

		$image_data['outcome'] 		= $rand_char;
		$image_data['image'] 		= $image;
		
		return $image_data;
	}
	
	function generateRandomChar($length=6)
	{	
	
		$paramsC 	= JComponentHelper::getParams('com_phocaguestbook') ;
		
		$charGroup = 'a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z';
		if ($paramsC->get( 'standard_captcha_chars' ) != '') {
			$charGroup = str_replace(" ", "", trim( $paramsC->get( 'standard_captcha_chars' ) ));
		}
		$charGroup = explode( ',', $charGroup );
	
		
		srand ((double) microtime() * 1000000);
		
		$random_string = "";
		
		for($i=0;$i<$length;$i++)
		{
			$random_char_group = rand(0,sizeof($charGroup)-1);
			
			$random_string .= $charGroup[$random_char_group];
		}
		return $random_string;
	}

	function getRandomImage()
	{
		$rand = mt_rand(1,4);
		$image = '0'.$rand.'.jpg';
		$image = JPATH_ROOT.'/components/com_phocaguestbook/assets/captcha/'. $image;
		return $image;
	}


	function getRandomFontColor()
	{
		$rand = mt_rand(1,6);
		if ($rand == 1) {$font_color[0] = 0; $font_color[1] = 0; $font_color[2] = 0;}
		if ($rand == 2) {$font_color[0] = 0; $font_color[1] = 0; $font_color[2] = 153;}
		if ($rand == 3) {$font_color[0] = 0; $font_color[1] = 102; $font_color[2] = 0;}
		if ($rand == 4) {$font_color[0] = 102; $font_color[1] = 51; $font_color[2] = 0;}
		if ($rand == 5) {$font_color[0] = 163; $font_color[1] = 0; $font_color[2] = 0;}
		if ($rand == 6) {$font_color[0] = 0; $font_color[1] = 82; $font_color[2] = 163;}
		return $font_color;
	}
}


// ================================================
class PhocaguestbookHelperCaptchaEasycalc
{
	/*$params->get('calc_opertor')
	$params->get('calc_operand')
	$params->get('calc_string');
	$params->get('calc_max_value', 20)  // Determine max. operand
	$params->get('calc_negativ') == 0)*/
	
	public function createCaptchaData($typeOfOperator, $numOfOperand, $convertToString, $maxValue, $useNegativ){
		$captcha = array(); 
		
        // Determine values
        if($typeOfOperator == 2) {
            $tcalc = mt_rand(1, 2);
        } elseif($typeOfOperator == 1) {
            $tcalc = 2;
        } else {
            $tcalc = 1;
        }
        
        if($numOfOperand == 2) {
            $toperand = 2;
        } elseif($numOfOperand == 3) {
            $toperand = 3;
        } else {
            $toperand = mt_rand(2, 3);
        }
                        
        //make numbers
        if ($useNegativ && ($tcalc == 2)) {
            $spam_check_1 = mt_rand($maxValue / 2, $maxValue);
            $spam_check_2 = mt_rand(1, $maxValue / 2);
            if($toperand == 3) {
                $spam_check_3 = mt_rand(0, $spam_check_1 - $spam_check_2);
            }
        } else {
			$spam_check_1 = mt_rand(1, $maxValue);
            $spam_check_2 = mt_rand(1, $maxValue);
            if($toperand == 3)  {
                $spam_check_3 = mt_rand(0, $maxValue);
            }
        }
        
		//operand 1
		$captcha['result']     = $spam_check_1;
		$captcha['challenge']  = JText::_('COM_PHOCAGUESTBOOK_CALC_CHALLENGE'). PhocaguestbookHelperCaptchaEasycalc::converttostring($spam_check_1, $convertToString);
        
    	//operand 2
    	$captcha['challenge'] .= ($tcalc == 1) ? ' '.JText::_('COM_PHOCAGUESTBOOK_CALC_PLUS').' ' : ' '.JText::_('COM_PHOCAGUESTBOOK_CALC_MINUS').' ';

		$captcha['result']    += ($tcalc == 1) ? $spam_check_2 : (-1 * $spam_check_2);
		$captcha['challenge'] .= PhocaguestbookHelperCaptchaEasycalc::converttostring($spam_check_2, $convertToString);

		//operand 3
		if($toperand == 3)  {
			$captcha['challenge'] .= ($tcalc == 1) ? ' '.JText::_('COM_PHOCAGUESTBOOK_CALC_PLUS').' ' : ' '.JText::_('COM_PHOCAGUESTBOOK_CALC_MINUS').' ';

			$captcha['result']    += ($tcalc == 1) ? $spam_check_3 : (-1 * $spam_check_3);
			$captcha['challenge'] .= PhocaguestbookHelperCaptchaEasycalc::converttostring($spam_check_3, $convertToString);
		}
		
		//done
		$captcha['challenge'] .=  ' '.JText::_('COM_PHOCAGUESTBOOK_CALC_EQUALS').' ';
				
		$captcha['result_encoded'] = base64_encode($captcha['result']);
		
		return $captcha;
	}
		
    // Convert numbers into strings, depending on type
    private function converttostring($x, $type)
    {
		switch ($type){
			case 0: $convert = 0;   break; //do not convert
			case 1: $convert = 1;   break; //convert
			default:
			case 2: $convert = mt_rand(0, 2); break;   // Probability 2/3 for conversion
		}
		
        if($convert >= 1) {
            if($x > 20) {
                return $x;	//not supported yet
            } else {
                // Names of the numbers are read from language file
                $names = array(JText::_('COM_PHOCAGUESTBOOK_CALC_NULL'), JText::_('COM_PHOCAGUESTBOOK_CALC_ONE'), JText::_('COM_PHOCAGUESTBOOK_CALC_TWO'), JText::_('COM_PHOCAGUESTBOOK_CALC_THREE'), 
					JText::_('COM_PHOCAGUESTBOOK_CALC_FOUR'), JText::_('COM_PHOCAGUESTBOOK_CALC_FIVE'), JText::_('COM_PHOCAGUESTBOOK_CALC_SIX'), JText::_('COM_PHOCAGUESTBOOK_CALC_SEVEN'), 
					JText::_('COM_PHOCAGUESTBOOK_CALC_EIGHT'), JText::_('COM_PHOCAGUESTBOOK_CALC_NINE'), JText::_('COM_PHOCAGUESTBOOK_CALC_TEN'), JText::_('COM_PHOCAGUESTBOOK_CALC_ELEVEN'), 
					JText::_('COM_PHOCAGUESTBOOK_CALC_TWELVE'), JText::_('COM_PHOCAGUESTBOOK_CALC_THIRTEEN'), JText::_('COM_PHOCAGUESTBOOK_CALC_FOURTEEN'), JText::_('COM_PHOCAGUESTBOOK_CALC_FIFTEEN'), 
					JText::_('COM_PHOCAGUESTBOOK_CALC_SIXTEEN'), JText::_('COM_PHOCAGUESTBOOK_CALC_SEVENTEEN'), JText::_('COM_PHOCAGUESTBOOK_CALC_EIGHTEEN'), JText::_('COM_PHOCAGUESTBOOK_CALC_NINETEEN'),
					JText::_('COM_PHOCAGUESTBOOK_CALC_TWENTY'));
                return $names[$x];
            }
        } else {
            return $x;
        }
    }
}

class PhocaguestbookHelperCaptchaHn
{
	////////////////////////////////
    //    PUBLIC PARAMS
    public $chars      = 6;  //How many chars the generated text should have
    public $minsize    = 20;  //The minimum size a Char should have
    public $maxsize    = 40; //The maximum size a Char can have
    public $maxrotation = 25;  //The maximum degrees a Char should be rotated. Set it to 30 means a random rotation between -30 and 30.
    public $noise        = TRUE; //Background noise On/Off (if is Off, a grid will be created)

    ////////////////////////////////
    //    PRIVATE & PROTECTED PARAMS
    private $noisefactor = 9;      // this will multiplyed with number of chars

	function createImageData()
	{
		$paramsC 	= JComponentHelper::getParams('com_phocaguestbook') ;
		$this->noise = $paramsC->get('hn_noise', TRUE);
		
		//get challenge
		$rand_char 	 	= PhocaguestbookHelperCaptchaHn::generateRandomChar($this->chars);
	
		// get number of noise-chars for background if is enabled
		$nb_noise 		= $this->noise ? ($this->chars * $this->noisefactor) : 0;
		// set dimension of image
		$lx 			= ($this->chars + 1) * (int)(($this->maxsize + $this->minsize) / 1.5);
        $ly 			= (int)(2.4 * $this->maxsize);
		
		$ttf[0]			= JPATH_COMPONENT . '/assets/captcha/fonts/essai.ttf';
		$ttf[1]			= JPATH_COMPONENT . '/assets/captcha/fonts/justus.ttf';
		//$ttf[2]			= JPATH_COMPONENT . '/assets/captcha/fonts/vera.ttf';
		$ttfmax			= 1; //max ttf[] idx

		// create Image and set the apropriate function depending on GD-Version & websafecolor-value
		$image = ImageCreateTruecolor($lx,$ly);
		// Set Backgroundcolor
		$rndcolor = PhocaguestbookHelperCaptchaHn::getRandomColor(224, 255);
		$back =  @ImageColorAllocate($image, $rndcolor[0], $rndcolor[1], $rndcolor[2]);
        @ImageFilledRectangle($image,0,0,$lx,$ly,$back);
        // allocates the 216 websafe color palette to the image
        //if($gd_version < 2 || $this->websafecolors) $this->makeWebsafeColors($image);

	   // fill with noise or grid
		if($nb_noise > 0) {
			// random characters in background with random position, angle, color
			for($i=0; $i < $nb_noise; $i++)
			{
				$rndcolor = PhocaguestbookHelperCaptchaHn::getRandomColor(160, 224);
				srand((double)microtime()*1000000);
				$size     = intval(rand((int)($this->minsize / 2.3), (int)($this->maxsize / 1.7)));
				$angle    = intval(rand(0, 360));
				$x        = intval(rand(0, $lx));
				$y        = intval(rand(0, (int)($ly - ($size / 5))));
				$color    = @ImageColorAllocate($image, $rndcolor[0], $rndcolor[1], $rndcolor[2]);
				$rand_ttf = mt_rand(0,$ttfmax);
				$text     = chr(intval(rand(45,250)));
				@ImageTTFText($image, $size, $angle, $x, $y, $color, $ttf[$rand_ttf], $text);
			}
		} else {
			// generate grid
			for($i=0; $i < $lx; $i += (int)($this->minsize / 1.5)) {
				$rndcolor = PhocaguestbookHelperCaptchaHn::getRandomColor(160, 224);
				$color    = @ImageColorAllocate($image, $rndcolor[0], $rndcolor[1], $rndcolor[2]);
				@imageline($image, $i, 0, $i, $ly, $color);
			}
			for($i=0 ; $i < $ly; $i += (int)($this->minsize / 1.8)) {
				$rndcolor = PhocaguestbookHelperCaptchaHn::getRandomColor(160, 224);
				$color    = @ImageColorAllocate($image, $rndcolor[0], $rndcolor[1], $rndcolor[2]);
				@imageline($image, 0, $i, $lx, $i, $color);
			}
		}

		// generate Text
		$char_string = '';
		$x = intval(rand($this->minsize,$this->maxsize));
		foreach ($rand_char as $key => $value) {
			srand((double)microtime()*1000000);
			$angle    = intval(rand(($this->maxrotation * -1), $this->maxrotation));
			$size     = intval(rand($this->minsize, $this->maxsize));
			$y        = intval(rand((int)($size * 1.5), (int)($ly - ($size / 7))));
			$rndcolor = PhocaguestbookHelperCaptchaHn::getRandomColor(0, 127);
			$color    = @ImageColorAllocate($image, $rndcolor[0], $rndcolor[1], $rndcolor[2]);
			$rndcolor = $this->getRandomColor(0, 127);
			$shadow    = @ImageColorAllocate($image, $rndcolor[0] + 127, $rndcolor[1] + 127, $rndcolor[2] + 127);
			$rand_ttf = mt_rand(0,$ttfmax);
			@ImageTTFText($image, $size, $angle, $x + (int)($size / 15), $y, $shadow, $ttf[$rand_ttf], $value);
			@ImageTTFText($image, $size, $angle, $x, $y - (int)($size / 15), $color, $ttf[$rand_ttf], $value);
			$x += (int)($size + ($this->minsize / 5));
			$char_string .= $value;
		}
        
		$image_data['outcome'] 		= $char_string;//$rand_char;
		$image_data['image'] 		= $image;
		
		return $image_data;
	}
	
	function generateRandomChar($length=6) {	
	
		$paramsC 	= JComponentHelper::getParams('com_phocaguestbook') ;
		
		$charGroup = 'a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z';
		if ($paramsC->get( 'ttf_captcha_chars' ) != '') {
			$charGroup = str_replace(" ", "", trim( $paramsC->get( 'hn_captcha_chars' ) ));
		}
		$charGroup = explode( ',', $charGroup );
	
		srand ((double) microtime() * 1000000);
		$random_array = array();
		for($i=0;$i<$length;$i++)
		{
			$random_char_group = rand(0,sizeof($charGroup)-1);
			$random_array[]	= $charGroup[$random_char_group];
		}
		return $random_array;
	}


	function getRandomColor($min,$max) {
		srand((double)microtime() * 1000000);
		$color[0] = intval(rand($min,$max));
		$color[1] = intval(rand($min,$max));
		$color[2] = intval(rand($min,$max));
		return $color;
	}


	
}




?>
