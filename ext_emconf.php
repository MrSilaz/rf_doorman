<?php


/***
 *
 * This file is part of the "Doorman" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2018 Ralf Freit <ralf@freit.de>, www.freit.de
 *
 ***/

$EM_CONF[$_EXTKEY] = [
	'title' => 'Doorman / Bouncer',
	'description' => 'With this extension you have a bouncer function to disable active users or to force new logins.',
	'category' => 'backend',
	'author' => 'Ralf Freit / www.freit.de',
	'author_email' => 'ralf@freit.de',
	'state' => 'stable',
	'internal' => '',
	'uploadfolder' => '0',
	'createDirs' => '',
	'clearCacheOnLoad' => 0,
	'version' => '1.0.0',
	'constraints' => [
		'depends' => [
			'typo3' => '7.6.0-8.7.99'
		],
		'conflicts' => [
		],
		'suggests' => [
		]
	]
];