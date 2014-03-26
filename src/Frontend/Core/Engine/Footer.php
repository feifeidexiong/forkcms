<?php

namespace Frontend\Core\Engine;

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

use Frontend\Core\Engine\Base\Object as FrontendBaseObject;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * This class will be used to alter the footer-part of the HTML-document that will be created by the frontend.
 *
 * @author Tijs Verkoyen <tijs@sumocoders.be>
 */
class Footer extends FrontendBaseObject
{
    /**
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        parent::__construct($kernel);

        $this->getContainer()->set('footer', $this);
    }

    /**
     * Parse the footer into the template
     */
    public function parse()
    {
        // get footer links
        $footerLinks = (array) Navigation::getFooterLinks();

        // assign footer links
        $this->tpl->assign('footerLinks', $footerLinks);

        // initial value for footer HTML
        $siteHTMLFooter = (string) Model::getModuleSetting('Core', 'site_html_footer', null);

        // facebook admins given?
        if (Model::getModuleSetting('Core', 'facebook_admin_ids', null) !== null || Model::getModuleSetting('core', 'facebook_app_id', null) !== null) {
            // build correct locale
            switch (FRONTEND_LANGUAGE) {
                case 'en':
                    $locale = 'en_US';
                    break;

                case 'zh':
                    $locale = 'zh_CN';
                    break;

                case 'cs':
                    $locale = 'cs_CZ';
                    break;

                case 'el':
                    $locale = 'el_GR';
                    break;

                case 'ja':
                    $locale = 'ja_JP';
                    break;

                case 'sv':
                    $locale = 'sv_SE';
                    break;

                case 'uk':
                    $locale = 'uk_UA';
                    break;

                default:
                    $locale = strtolower(FRONTEND_LANGUAGE) . '_' . strtoupper(FRONTEND_LANGUAGE);
            }

            // add Facebook container
            $siteHTMLFooter .= "\n" . '<div id="fb-root"></div>' . "\n";

            // add facebook JS
            $siteHTMLFooter .= '<script>' . "\n";
            if (Model::getModuleSetting('Core', 'facebook_app_id', null) !== null) {
                $siteHTMLFooter .= '  window.fbAsyncInit = function() {' . "\n";
                $siteHTMLFooter .= '    FB.init({ appId: "' .
                                   Model::getModuleSetting('Core', 'facebook_app_id', null) .
                                   '", status: true, cookie: true, xfbml: true, oauth: true });' . "\n";
                $siteHTMLFooter .= '  jsFrontend.facebook.afterInit();' . "\n";
                $siteHTMLFooter .= '  };' . "\n";
            }
            $siteHTMLFooter .= '  (function(d){' . "\n";
            $siteHTMLFooter .= '    var js, id = \'facebook-jssdk\', ref = d.getElementsByTagName(\'script\')[0];' . "\n";
            $siteHTMLFooter .= '    if(d.getElementById(id)) { return; }' . "\n";
            $siteHTMLFooter .= '    js = d.createElement(\'script\'); js.id = id; js.async = true; js.src = "//connect.facebook.net/' . $locale . '/all.js";' . "\n";
            $siteHTMLFooter .= '    ref.parentNode.insertBefore(js, ref);' . "\n";
            $siteHTMLFooter .= '  }(document));' . "\n";
            $siteHTMLFooter .= '</script>';
        }

        // assign site wide html
        $this->tpl->assign('siteHTMLFooter', $siteHTMLFooter);
    }
}