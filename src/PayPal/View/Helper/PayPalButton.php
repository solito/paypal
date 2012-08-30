<?php
namespace PayPal\View\Helper;

use PayPal\Service\WPSToolkit;
use PayPal\Button\Button;

/**
 * A view helper for creating PayPal buttons.
 * 
 * @author Thomas Lhotta
 */
class PayPalButton extends \Zend\View\Helper\AbstractHelper
{
    /**
     * @var WPSToolkit
     */
    protected $wpsToolkit;
    
    /**
     * The base path to PayPal buttons.
     * 
     * @var string 
     */
    protected $baseURL = 'https://www.paypal.com/%s/i/btn';

    /**
     * A map of allowed locales an their corresponding paypal buttons.
     * 
     * Values represent the allowed locales, keys the mapped papal button path.
     * 
     * @var array
     */
    protected $locales = array(
        'de_DE'   => 'de',
        'en_US'   => 'en',
        'en_GB'   => 'en_GB',
        'it_IT'   => 'it',                                  
    );
    
    public function __construct(WPSToolkit $wpsToolKit)
    {
        $this->wpsToolkit = $wpsToolKit;
    }
    
    /**
     * Create a paypal button.
     * 
     * @param array|Button $params 
     * @param string $image The image filename to use.
     * @return string
     */
    public function __invoke($params, $image)
    {
        if ($params instanceof Button) {
            $params = $params->getParams();
        }
        
        $button = $this->wpsToolkit->createEncryptedButton($params, $this->getImage($image));
        
        return $button['encryptedButton'];
    }
    
    /**
     * Build an image url.
     * 
     * @param string $type
     * @return string
     */
    public function getImage($type)
    {
        $translator = $this->getView()->plugin('translate')->getTranslator();
        $locale = \Locale::lookup(
            $this->locales,
            $translator->getLocale(),
            false, 
            \Locale::lookup($this->locales, $translator->getFallbackLocale())
        );
        
        return sprintf($this->baseURL, array_search($locale, $this->locales)) . '/' . $type; 
    }
}