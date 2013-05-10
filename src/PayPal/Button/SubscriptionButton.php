<?php
namespace PayPal\Button;

/**
 * Class representation of a paypal button.
 *
 * See https://cms.paypal.com/uk/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_html_Appx_websitestandard_htmlvariables
 * for variables
 *
 * @author Thomas Lhotta
 */
class SubscriptionButton extends AbstractButton
{
	
    /**
     * An anrray of button parameters.
     *
     * @var array
     */
    protected $params = array(
        'cmd' => '_xclick-subscriptions',
        't3' => 'D'
    );


    /**
     * Regular subscription price. 
     *
     * @param float $amount
     * @return \PayPal\Button\Button
     */
    public function setAmount($amount)
    {
        $this->setParam('amount', $amount);
        return $this->setParam('a3', $amount);
    }
    
    public function setDuration($duration)
    {
        return $this->setParam('p3', $duration);
    }
    
    public function setUnsubscribe($text, $flag = true)
    {
        $this->setParam('unsubscribe', true);   
        return $this->setParam('unsubscribeText', $text);
    } 

}