<?php
namespace PayPal\Service;

class IPN
{
    protected $environment;

    protected $userName;

    protected $cacertPath;

    public function __construct($config)
    {
        $this->environment = $config['environment'];
        $this->userName = $config['userName'];
        $this->cacertPath = $config['cacert'];
    }

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

    }


}