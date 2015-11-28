<?php

/*
	Question2Answer (c) Gideon Greenspan

	http://www.question2answer.org/

	
	File: /qa-plugin/q2a-logical-captcha/qa-logical-captcha.php
	Version: See define()s at top of qa-include/qa-base.php
	Description: Captcha module for VSBP


	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	More about this license: http://www.question2answer.org/license.php
*/

	if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
		header('Location: ../');
		exit;
	}


    # Set number question for questy
    # sudo pear install channel://pear.php.net/Numbers_Words-0.16.2
    # http://pear.php.net/package-info.php?package=Numbers_Words 
    require_once("Numbers/Words.php");


	class qa_number_captcha {
		const SAVE_BTN             = 'ami_ncap_save' ;
		const NUMBER_LENGTH        = 'ami_ncap_number_length' ;
		const SALT                 = 'ami_ncap_salt' ;
		const CAPTCHA_ANSWER       = 'ami_ncap_answer' ;
		const CAPTCHA_HIDDEN_FIELD = 'ami_ncap_answer_hidden[]' ;
		const CAPTCHA_HIDDEN_NAME  = 'ami_ncap_answer_hidden' ;
		
		var $directory;
		
		function load_module($directory, $urltoroot) {
			$this->directory=$directory;
		}

		function option_default($option) {
			if($option == self::NUMBER_LENGTH)
				return '9' ;
			if($option == self::SALT)
				return 'number-captcha-123456789' ;
		}

		function admin_form() {
			$saved=false;
			
			if (qa_clicked(self::SAVE_BTN)) {
				qa_opt(self::NUMBER_LENGTH , qa_post_text(self::NUMBER_LENGTH));
				qa_opt(self::SALT , qa_post_text(self::SALT));
				$saved=true;
			}

			$form=array(
				'ok' => $saved ? qa_lang('admin/options_saved') : null,
				'fields' => array(
					self::NUMBER_LENGTH => array(
						'type' => 'text',
						'tags' => 'name="'.self::NUMBER_LENGTH.'"',
						'label' => qa_lang_html('ncap/number_length'),
						'value' => qa_opt(self::NUMBER_LENGTH),
						'note' => qa_lang('ncap/get_the_number_length'),
					),
					self::SALT => array(
						'type' => 'text',
						'tags' => 'name="'.self::SALT.'"',
						'label' => qa_lang_html('ncap/salt'),
						'value' => qa_opt(self::SALT),
						'note' => qa_lang('ncap/get_the_salt'),
					),
				),
				'buttons' => array(
					array(
						'label' => qa_lang_html('main/save_button'),
						 'tags' => 'NAME="'.self::SAVE_BTN.'"',
					),
				),
			);
			return $form;
		}

		function allow_captcha()
		{
			try
			{
				$numWords = new Numbers_Words();
				if (strlen($numWords->toWords(100)) > 1) {
					return true;
				}
			} catch (Exception $e) {
				return 'Unable to execute Numbers_Words.';
				return false;
			}
		}

		function form_html(&$qa_content, $error) {
			$label = qa_lang('ncap/please_answer');

	        $myChallengeNumber = rand(0, 899999999) + 100000000;
			$myChallengeString = (string)$myChallengeNumber;
			$numWords = new Numbers_Words();
		    $myChallengeStringLong = $numWords->toWords($myChallengeNumber);
		    $myChallengeIndex = rand(0, 8) + 1;

		    $myChallengePositions = array (
		        'first',
		        'second',
		        'third',
		        'fourth',
		        'fifth',
		        'sixth',
		        'seventh',
		        'eighth',
		        'ninth'
		    );
		    $myChallengePositionName = $myChallengePositions[$myChallengeIndex - 1];
			
			$question = (string) "What is the ".$myChallengePositionName." digit of the number <strong>".$myChallengeStringLong."</strong>?";
			$ans_hidden_field = '<input type="hidden" value="'.md5(qa_opt(self::SALT).$myChallengeString[$myChallengeIndex - 1]).'" name="'.self::CAPTCHA_HIDDEN_FIELD.'" />'.PHP_EOL ;
			
			$html  = '<div class="qa-ncap">
						<div class="qa-ncap-label">'.$label.'</div>			
							<label class="qa-ncap-question" for="'.self::CAPTCHA_ANSWER.'" style="font-weight:700;">'.$question.'</label>	
							<input class="qa-ncap-answer" name="'.self::CAPTCHA_ANSWER.'" id="'.self::CAPTCHA_ANSWER.'" type="text" autocapitalize="off" autocorrect="off" autocomplete="off" required="required"/>
							'. $ans_hidden_field .'
					  </div>' ;
			
			return $html;
		}

		function validate_post(&$error) {
			$hashed_answer = (array)@$_POST[self::CAPTCHA_HIDDEN_NAME];

			if (empty($hashed_answer)) {
				$error = qa_lang('ncap/invalid_verification_code');
				return false;
			}

			$user_answer = trim(@$_POST[self::CAPTCHA_ANSWER]);
			if (empty($user_answer)) {
				$error = qa_lang('ncap/answer_to_the_q');
				return false;
			}

			$user_answer = strtolower($user_answer); 
			$user_answer = md5(qa_opt(self::SALT).$user_answer) ;
			
			if (!in_array($user_answer, $hashed_answer )){
				// verification failed 
				$error = qa_lang('ncap/answer_to_the_q');
				return false;
			}else {
				return true;
			}

			return false;
		}
		
	}
	

/*
	Omit PHP closing tag to help avoid accidental output
*/
