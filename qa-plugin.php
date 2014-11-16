<?php

/*
	Question2Answer (c) Gideon Greenspan
	Q2A Number Captcha (c) Jamie Thingelstad

	http://www.question2answer.org/

	
	File: /qa-plugin/q2a-number-captcha/qa-plugin.php
	Version: See define()s at top of qa-include/qa-base.php
	Description: Initiates reCAPTCHA plugin


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

/*
	Plugin Name: Q2A Number Captcha
	Plugin URI: https://github.com/thingles/q2a-number-captcha
	Plugin Description: Protect bot action by adding simple number question to Q2A form.
	Plugin Version: 1.0
	Plugin Date: 2014-11-15
	Plugin Author: Jamie Thingelstad
	Plugin Author URI: http://thingelstad.com/
	Plugin License: GPLv2
	Plugin Minimum Question2Answer Version: 1.6
	Plugin Update Check URI: https://raw.github.com/thingles/q2a-number-captcha/master/qa-plugin.php
*/

	if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
		header('Location: ../../');
		exit;
	}

	qa_register_plugin_phrases('qa-number-captcha-lang-*.php', 'ncap');
	qa_register_plugin_module('captcha', 'qa-number-captcha.php', 'qa_number_captcha', 'Q2A Number Captcha');
	

/*
	Omit PHP closing tag to help avoid accidental output
*/