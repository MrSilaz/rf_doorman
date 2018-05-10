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

defined('TYPO3_MODE') or die();

if (TYPO3_MODE === 'BE') {
    $GLOBALS['TYPO3_CONF_VARS']['BE']['toolbarItems'][1521216148] = \Ralffreit\RfDoorman\Backend\DoormanItem::class;


    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
    $iconRegistry->registerIcon(
        'tx-doorman',
        \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        ['source' => 'EXT:rf_doorman/Resources/Public/Icons/doorman.svg']
    );

}
