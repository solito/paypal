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
class BuynowButton extends AbstractButton
{
	
    /**
     * An anrray of button parameters.
     *
     * @var array
     */
    protected $params = array(
        'cmd' => '_xclick',
    );


    /**
     * Get a single parameter.
     *
     * @param string $name
     * @throws \InvalidArgumentException
     * @return multitype:
     */
    public function getParam($name)
    {
        if (!array_key_exists($name, $this->getParams())) {
            throw new \InvalidArgumentException(
                sprintf('Param %s is not set.' , $name)
            );
        }

        return $this->params[$name];
    }

    /**
     * Set the button type.
     *
     * @param string $cmd
     * @return \PayPal\Button\\PayPal\Button\Button
     */
    public function setCmd($cmd)
    {
        $allowedTypes = array(
            ' _xclick ',
            ' _cart',
            '_oe-gift-certificate',
            '_xclick-subscription',
            '_xclick-auto-billing',
            '_xclick-payment-plan',
            '_donations',
            '_s-xclick'
        );
        
        return $this->setParam('cmd', $cmd);
    }

    /**
     * The price or amount of the product. If omitted from Buy Now or Donate
     * buttons, buyers enter their own amount at the time of payment.
     *
     * @param float $amount
     * @return \PayPal\Button\Button
     */
    public function setAmount($amount)
    {
        return $this->setParam('amount', $amount);
    }

    /**
     * Pass-through variable. The value you specify is passed back to you upon
     * payment completion. Required if to track inventory or track profit and
     * loss for the item the button sells on Paypal.
     *
     * Sets the item_number button variable.
     *
     * @param string $number
     * @throws \InvalidArgumentException
     * @return \PayPal\Button\Button
     */
    public function setItemNumber($number)
    {
        if (strlen($number) > 127) {
            throw new \InvalidArgumentException(
                'The given item number must not exeed 127 characters.'
            );
        }

        return $this->setParam('iten_number', $number);
    }

    /**
     * Set the quantity.
     *
     * Sets the quantity button variable.
     *
     * @param integer $quantity
     * @return \PayPal\Button\Button
     */
    public function setQuantity($quantity)
    {
        if ($quantity < 1) {
            throw new \InvalidArgumentException(
                'Quantity must be at least 1.'
            );
        }

        return $this->setParam('quantity', $quantity);
    }
}