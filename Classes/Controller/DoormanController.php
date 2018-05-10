<?php
namespace Ralffreit\RfDoorman\Controller;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

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


/**
 * DoormanController
 */
class DoormanController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * Doorman activities
     *
     * @return void
     */
    public function doormanActivities()
    {
        $handleUserId = GeneralUtility::_GP('user');
        $handleUserType = GeneralUtility::_GP('usertype');
        $status = ['status' => 'ok', 'error' => ''];
        $affRows = 0;

        switch (GeneralUtility::_GP('handleType')) {
            case 'hide':
                $affRows = $this->hideUser($handleUserId, $handleUserType);
             break;
            case 'kick':
               $affRows = $this->removeSession($handleUserId, $handleUserType);
             break;
        }

        if (!$affRows) {
            $status = ['status' => 'error', 'error' => LocalizationUtility::translate('error.nodata', 'rf_doorman')];
        }

        echo json_encode($status);
        die();
    }

    /**
     * Remove session ID
     *
     * @var int handleUserId  FE/BE UserId
     * @var string handleUserType  feuser / beuser
     *
     * @return void
     */
    public function removeSession($handleUserId, $handleUserType)
    {
        $dbTable = ($handleUserType == 'beuser'?'be_sessions':'fe_sessions');
        $GLOBALS['TYPO3_DB']->exec_DELETEquery($dbTable, 'ses_userid = '.(int)$handleUserId);
        return $GLOBALS['TYPO3_DB']->sql_affected_rows();
    }

    /**
     * hide and remove session ID
     *
     * @var int handleUserId  FE/BE UserId
     * @var string handleUserType  feuser / beuser
     *
     * @return void
     */
    public function hideUser($handleUserId, $handleUserType)
    {
        $affRows = $this->removeSession($handleUserId, $handleUserType);

        if ($affRows) {
            $dbTable = ($handleUserType == 'beuser'?'be_users':'fe_users');
            $GLOBALS['TYPO3_DB']->exec_UPDATEquery($dbTable, 'uid = '.(int)$handleUserId, ['disable' => 1]);
            $affRows = $GLOBALS['TYPO3_DB']->sql_affected_rows();
        }

        return $affRows;
    }
}
