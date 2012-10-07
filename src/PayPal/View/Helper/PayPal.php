<?php
namespace PayPal\View\Helper;

/**
 * A view helper accessing default paypal settings.
 *
 * @author Thomas Lhotta
 */
class PayPal extends \Zend\View\Helper\AbstractHelper
{
    /**
     * @var array
     */
    protected $config;

    /**
     * Create a new paypal view helper.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Returns true if the paypal system is enabled.
     *
     * @return boolean
     */
    public function isEnabled()
    {
        $config = $this->config;
        if (isset($config['enabled']) and $config['enabled'] === true) {
            return true;
        }

        return false;
    }

}