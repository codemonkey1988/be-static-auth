<?php
declare(strict_types=1);
namespace Codemonkey1988\BeStaticAuth\LoginProvider;

use TYPO3\CMS\Backend\Controller\LoginController;
use TYPO3\CMS\Backend\LoginProvider\LoginProviderInterface;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;

/**
 * Class StaticAdminProvider
 */
class StaticAdminProvider implements LoginProviderInterface
{
    /**
     * Renders the login mask for static authentication.
     *
     * @param StandaloneView $view
     * @param PageRenderer $pageRenderer
     * @param LoginController $loginController
     */
    public function render(StandaloneView $view, PageRenderer $pageRenderer, LoginController $loginController)
    {
        $view->setTemplatePathAndFilename(GeneralUtility::getFileAbsFileName('EXT:be_static_auth/Resources/Private/Templates/Backend.html'));
    }
}
