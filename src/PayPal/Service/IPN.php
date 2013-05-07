<?php
namespace PayPal\Service;

/**
 * A service to validate Paypal IPN messages.
 * 
 * @author Thomas Lhotta
 *
 */
class IPN
{
    /**
     * The paypal environment
     * 
     * @var string
     */
    protected $environment;

    /**
     * The receivers email address.
     * 
     * @var string
     */
    protected $receiverName;

    /**
     * The path to the paypal CA certificate
     * 
     * @var string
     */
    protected $cacertPath;

    
    /**
     * Creates an IPN Service
     * 
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->environment = $config['environment'];
        $this->userName = $config['business'];
        $this->cacertPath = $config['cacert'];
    }

    /**
     * Returns true if the IPN message is valid.
     * 
     * @param array $data
     * @return boolean
     */
    public function verifyIPNMessage(array $data)
    {
        $req = 'cmd=' . urlencode('_notify-validate');

        foreach ($data as $key => $value) {
        	$value = urlencode(stripslashes($value));
        	$req .= "&$key=$value";
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->environment .'/cgi-bin/webscr');
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_CAINFO, $this->cacertPath);
        #curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: www.paypal.com'));
        $res = curl_exec($ch);

        curl_close($ch);
        if (strcmp ($res, "VERIFIED") !== 0) {
        	return false;
        }

        return true;
    }

    public function verifyReceiver($data)
    {
        if (array_key_exists('receiver_email', $data)) {
            if ($this->userName == $this->userName) {
                return true;
            }
        }
        return false;
    }


}