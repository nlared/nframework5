<?php

$config=[
	'mongo_connection_string'=>'mongodb://127.0.0.1',
	'cookie_domain' =>$_SERVER['HTTP_HOST'],
	'sitedb' => 'nlared',
	'google-site-verification' => '',
	'google-captcha-key' => '',
	'google-captcha-secret' => '',
	'google-captcha-invisible-key'=>'',
	'google-captcha-invisible-secret'=>'',
	'title' => $_SERVER['HTTP_HOST'],
	'short_name'=>'NF5',
	'author' => 'JEFR',
	'subject' => '',
	'description' => $_SERVER['HTTP_HOST'],
	'keywords' => '',
	'image'=>'https://'.$_SERVER['HTTP_HOST'].'/img/nlaredlogo5.png',
	'canregister' => false,
	'mailhost' => 'smtp.domain.com',
	'mailusername' => 'account@domain.com',
	'mailpassword' => 'password',
	'mailport' => 587,
	'mailsmtpauth' => true,
	'mailcrypt' => false,
	'mailauth' => true,
	'notifications'=>[
		"subject"=> "mailto:account@domain.com",
		"publicKey"=> "",
		"privateKey"=> ""
	],
	'manifest'=>[
		'background_color'=>'#ffffff',
		'theme_color'=>'#13709e',
	]
];