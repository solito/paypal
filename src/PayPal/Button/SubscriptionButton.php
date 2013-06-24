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
        'src' => '1',
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
    
    /**
     * Set the interval at which the payments should occur according to the
     * given unit. The default unit is days.
     * 
     * @param integer $duration
     * @return \PayPal\Button\SubscriptionButton
     */
    public function setDuration($duration, $unit = 'D')
    {
        $this->setParam('t3', $unit);
        return $this->setParam('p3', $duration);
    }
    
    /**
     * Set the text to be displayed in the unsubscribe button.
     * 
     * @param string $text
     * @param string $flag
     * @return \PayPal\Button\SubscriptionButton
     */
    public function setUnsubscribe($text, $flag = true)
    {
        $this->setParam('unsubscribe', true);   
        return $this->setParam('unsubscribeText', $text);
    } 
    
    /**
     * Set the number of cycles after which the subscription expires.
     * 
     * Default is never
     * 
     * @param integer $recurrences
     */
    public function setRecurrences($recurrences)
    {
        return $this->setParam('srt', $recurrences);
    }

}