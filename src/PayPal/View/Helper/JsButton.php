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

	    $data['env'] = 'sandbox';
	    
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