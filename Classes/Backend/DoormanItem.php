<?php
namespace Ralffreit\RfDoorman\Backend;


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

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\Toolbar\ToolbarItemInterface;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Core\Utility\PathUtility;

/**
 * Render system info toolbar item
 */
class DoormanItem implements ToolbarItemInterface
{
    /**
     * @var StandaloneView
     */
    protected $standaloneView = null;

    /**
     * Template file for the dropdown menu
     */
    const TOOLBAR_MENU_TEMPLATE = 'Resources/Private/Templates/ToolbarMenu/Doorman.html';

    /**
     * @var IconFactory
     */
    protected $iconFactory;

    /**
     * Toolbar Index
     */
    const TOOLBAR_INDEX = 75;

    /**
     * Constructor
     */
    public function __construct()
    {
        if (!$this->checkAccess()) {
            return;
        }

        $this->loadJSFile();
        $this->iconFactory = GeneralUtility::makeInstance(IconFactory::class);
        $this->standaloneView = GeneralUtility::makeInstance(StandaloneView::class);
        $this->standaloneView->setTemplatePathAndFilename(ExtensionManagementUtility::extPath('rf_doorman') . static::TOOLBAR_MENU_TEMPLATE);
    }


    /**
     * Renders the menu for AJAX calls
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function renderMenuAction(ServerRequestInterface $request, ResponseInterface $response)
    {
        $response->getBody()->write($this->getDropDown());
        $response = $response->withHeader('Content-Type', 'text/html; charset=utf-8');
        return $response;
    }


    /**
     * Checks whether the user has access to this toolbar item
     *
     * @return bool TRUE if user has access, FALSE if not
     */
    public function checkAccess()
    {
        return $GLOBALS['BE_USER']->isAdmin();
    }

    /**
     * Render system information dropdown
     *
     * @return string Icon HTML
     */
    public function getItem()
    {
        $icon = $this->iconFactory->getIcon('tx-doorman', Icon::SIZE_DEFAULT)->render('inline');
        return '<span title="Doorman">' . $icon . '<span id="t3js-doorman-counter" class="badge badge-success"></span></span>';
    }

    /**
     * Render drop down
     *
     * @return string Drop down HTML
     */
    public function getDropDown()
    {
        global $BE_USER;

        if (!$this->checkAccess()) {
            return '';
        }

        $this->standaloneView->assignMultiple([
            'beusers' => $this->getAllUsers('be_users', 'be_sessions'),
            'feusers' => $this->getAllUsers('fe_users', 'fe_sessions'),
            'currentUser' => $BE_USER->user
        ]);

        return $this->standaloneView->render();
    }

    /**
     * No additional attributes needed.
     *
     * @return array
     */
    public function getAdditionalAttributes()
    {
        return [];
    }

    /**
     * This item has a drop down
     *
     * @return bool
     */
    public function hasDropDown()
    {
        return true;
    }

    /**
     * Position relative to others
     *
     * @return int
     */
    public function getIndex()
    {
        return TOOLBAR_INDEX;
    }


    /**
     * Returns current PageRenderer
     *
     * @return PageRenderer
     */
    protected function getPageRenderer()
    {
        return GeneralUtility::makeInstance(PageRenderer::class);
    }

    protected function loadJSFile()
    {
        $fullJsPath = GeneralUtility::getFileAbsFileName('EXT:rf_doorman/Resources/Public/JavaScript/');
        $fullJsPath = PathUtility::getRelativePath(PATH_site, $fullJsPath);
        $pageRenderer =  GeneralUtility::makeInstance(PageRenderer::class);
        $pageRenderer->addRequireJsConfiguration([
            'paths' => [
                'Ralffreit/RfDoorman' => PathUtility::getRelativePath(PATH_site, $fullJsPath)
            ],
            'shim' => [
                'Ralffreit/RfDoorman' => ['jquery']
            ]
        ]);
        $pageRenderer->loadRequireJsModule('TYPO3/CMS/RfDoorman/rf_doorman');
    }

    public function getAllUsers($tblUser, $tblSession)
    {
        $additionalSelect = ($tblUser == 'be_users' ? 'usr.realName, usr.admin' : 'usr.name as realName');
        $dbSelect = 'ses.ses_id, ses.ses_userid, ses.ses_tstamp, usr.username,'.$additionalSelect;
        $dbTable  = $tblSession.' as ses LEFT OUTER JOIN '.$tblUser.' as usr ON ses.ses_userid = usr.uid';
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($dbSelect, $dbTable, 'usr.deleted=0 AND usr.disable=0');

        while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
            $data[] = $row;
        }

        return $data;
    }
}
