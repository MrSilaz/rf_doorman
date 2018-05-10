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

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}
if (TYPO3_MODE === 'BE') {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerAjaxHandler(
        'handleDoorman',
        'Ralffreit\\RfDoorman\\Controller\\DoormanController->doormanActivities'
    );

    $TBE_STYLES['skins'][$_EXTKEY]['name'] = 'rf_doorman';
    $TBE_STYLES['skins'][$_EXTKEY]['stylesheetDirectories']['structure'] = 'EXT:' . ($_EXTKEY) . '/Resources/Public/Css/';
}
