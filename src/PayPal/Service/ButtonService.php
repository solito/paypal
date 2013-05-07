<?php
namespace PayPal\Service;

use PayPal\Button;

class ButtonService
{
    protected $business = null;
    
    protected $notifyUrl = null;
    
    public function __construct(array $config)
    {
        if (isset($config['business'])) {
            $this->setBusiness($config['business']);
        }
        
        if (isset($config['notifyUrl'])) {
            $this->setNotifyUrl($config['notifyUrl']);
        }
    }
    
    public function setBusiness($business)
    {
        $this->business = $business;
        return $this;
    }
    
    public function getBusiness()
    {
        return $this->business;
    }
    
    public function setNotifyUrl($url)
    {
        $val = new \Zend\Validator\Uri();
        
        if (!$val->isValid($url)) {
            throw new \InvalidArgumentException(
                'The paypal notify URI does not appear to be valid.'
            );
        }
        
        $this->notifyUrl = $url;
        
        return $this;
    }
    
    public function getNotifyUrl()
    {
        return $this->notifyUrl;
    }
    
    public function createButton($type = 'buynow')
    {
        $button = null;
        
        switch ($type) {
            case 'buynow':
                $button = new Button\BuynowButton();
            break;
            case 'subscription':
                $button = new Button\SubscriptionButton();
            break;
            
            default:
                throw new \InvalidArgumentException('Invalid button type');
            break;
        }
        
        if ($this->getBusiness()) {
            $button->setBusiness($this->getBusiness());
        }
        
        if ($this->getNotifyUrl()) {
            $button->setNotifyURL($this->getNotifyUrl());
        }
        
        return $button;
    }
}