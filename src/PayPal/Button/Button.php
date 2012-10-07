<?php
namespace PayPal\Button;

/**
 * Class representation of a paypal button.
 *
 * @author Thomas Lhotta
 */
class Button
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
     * An array of currency codes accepted by paypal.
     *
     * @var array
     */
    protected $allowedCurrencies = array(
        'AUD',
        'CAD',
        'CZK',
        'DKK',
        'EUR',
        'HKD',
        'HUF',
        'JPY',
        'NOK',
        'NZD',
        'PLN',
        'GBP',
        'SGD',
        'SEK',
        'CHF',
        'USD'
    );

    /**
     * Returns an array of button parameters.
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Set a button parameter.
     *
     * See http://www.paypalobjects.com/IntegrationCenter/ic_std-variable-ref-buy-now.html
     * for a lis of parameters
     *
     * @param string $name
     * @param mixed $value
     */
    public function setParam($name, $value)
    {
        $this->params[$name] = $value;
        return $this;
    }

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
     * The URL to which PayPal posts information about the payment, in the
     * form of Instant Payment Notification messages.
     *
     * @param string $url
     * @return \PayPal\Button\Button
     */
    public function setNotifyURL($url)
    {
        if (strlen($url) > 255) {
            throw new \InvalidArgumentException(
                'The given URL must not exeed 255 characters.'
            );
        }

        return $this->setParam('notify_url', $url);
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
     * Description of the item. If omitted, buyers enter their own name.
     *
     * @param string $name
     * @return \PayPal\Button\Button
     */
    public function setItemName($name)
    {
        if (strlen($name) > 127) {
            throw new \InvalidArgumentException(
                'The given item name must not exeed 127 characters.'
            );
        }
        return $this->setParam('item_name', $name);
    }

    /**
     * Pass-through variable. The value you specify is passed back to you upon
     * payment completion. Required if to track inventory or track profit and
     * loss for the item the button sells on Paypal.
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

    /**
     * Pass-through variable fortracking purposes, which buyers do not see.
     *
     * @param string $custom
     * @throws \InvalidArgumentException
     * @return \PayPal\Button\Button
     */
    public function setCustom($custom)
    {
        if (strlen($custom) > 256) {
            throw new \InvalidArgumentException(
                'The given custom string must not exeed 256 characters.'
            );
        }

        return $this->setParam('custom', $custom);
    }

    /**
     * Pass-through variable fortracking purposes.
     *
     * @param string $invoice
     * @throws \InvalidArgumentException
     * @return \PayPal\Button\Button
     */
    public function setInvoice($invoice)
    {
        if (strlen($invoice) > 127 ) {
            throw new \InvalidArgumentException(
                'The given invoice number must not exeed 127 characters.'
            );
        }

        return $this->setParam('invoice', $invoice);
    }

    /**
     * Set the currency code.
     *
     * @param string $currencyCode
     * @throws \InvalidArgumentException
     * @return \PayPal\Button\Button
     */
    public function setCurrencyCode($currencyCode)
    {
        if (!in_array($currencyCode, $this->allowedCurrencies)) {
            throw new \InvalidArgumentException(
                'Invalid currency code given.');
        }

        return $this->setParam('currency_code', $currencyCode);
    }

    /**
     * Set the return url an method the user is returned to after payment.
     *
     * Allowed optional methods are GET and POST. If no method is set GET
     * is used an no payment data is transmitted.
     *
     * @param string $url
     * @param string $method
     * @throws \InvalidArgumentException
     * @return \PayPal\Button\Button
     */
    public function setReturnURL($url, $method = 'GET')
    {
        $allowedMethods = array (
            1 => 'GET',
            2 => 'POST',
        );

        if ($method) {
            if (!in_array($method, $allowedMethods)) {
                throw new \InvalidArgumentException(
                    'Invalid currency code given.');
            }
            $this->setParam('rm', array_search($method, $allowedMethods));
        }


        $this->setParam('rm', $method);
        return $this->setParam('return', $url);
    }

    /**
     * Sets the URL the user is returned to when payment was canceled.
     *
     * @param string $url
     * @return \PayPal\Button\Button
     */
    public function setCancleReturnURL($url)
    {
        return $this->setParam('cancel_return', $url);
    }

}