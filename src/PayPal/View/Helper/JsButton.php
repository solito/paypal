<?php
namespace PayPal\View\Helper;

use PayPal\Button\AbstractButton;
use Zend\I18n\Translator\TranslatorAwareInterface;
use Zend\I18n\Translator\TranslatorAwareTrait;
use \Zend\View\Helper\AbstractHtmlElement;


/**
 * A view helper for creating PayPal Javascript buttons.
 *
 * @see http://paypal.github.io/JavaScriptButtons/
 *
 * @author Thomas Lhotta
 */
class JsButton extends AbstractHtmlElement implements TranslatorAwareInterface
{
    use TranslatorAwareTrait;
    
	protected $jsAdded = false;
	
	protected $jsPath = "js/libs/paypal-button.js";
	
	public function setJsPath($path) 
	{
		$this->jsPath = $path;
		return $this;
	}
	
	public function getJsPath()
	{
		return $this->jsPath;
	}
	
	public function __invoke($data)
	{
	    if ($data instanceof AbstractButton) {
	        $data = $data->getParams();
	    }

	    if (isset($data['unsubscribe'])) {
	        if ($data['unsubscribe'] == true) {
	            return $this->createCancleSubscriptionButton($data);
	        }
	        unset($data['unsubscribe']);
	        unset($data['unsubscribeText']);
	    }
	    
	    return $this->createStandardButton($data);
	    
	}
	
	public function createCancleSubscriptionButton($data)
	{
	    $uri = 'https://www.paypal.com/cgi-bin/webscr—cmd=_subscr-find&alias=';
	    $uri .= urlencode($data['business']);
	    return '<a class="unsubscribe" href="' . $uri . '">' . $data['unsubscribeText'] . '</a>';
	}
	
	public function createStandardButton(array $data) 
	{
	    $data = $this->convertCommand($data);
	     
	    if (isset($data['business'])) {
	        $business = $data['business'];
	    } else {
	        throw new \RuntimeException('Business is not set.');
	    }
	     
	    if ($this->hasTranslator()) {
	        //$locale = $this->getTranslator()->getLocale();
	        //$data['locale'] = $locale;
	        //$data['lc'] = $locale;
	    }
	     
	    foreach ($data as $key => $value) {
	        $newData['data-' . $key] = $value;
	    }
	    
	    $newData['src'] = $this->getJsPath() . '?merchant=' . $business;
	    
	    
	    $return = '<script' . $this->htmlAttribs($newData);
	    $return .= $this->getClosingBracket() . '</script>';
	    return $return;
	}
	
	protected function convertCommand($data)
	{
	    if (!isset($data['cmd'])) {
	        $data['cmd'] = '_xclick';
	    }
	    
	    $commands = array(
    	    '_xclick' => 'buynow',
    	    '_cart' => 'cart',
    	    '_xclick-subscriptions' => 'subscribe',
    	    '_donations' => 'donate',
	    );
	    if (!isset($commands[$data['cmd']])) {
	        throw new \InvalidArgumentException('Invalid command given.');
	    }
	    
	    $data['button'] = $commands[$data['cmd']];
	    return $data;
	}
	
}