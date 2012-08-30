<?php
namespace PayPal\Service;

class WPSToolkit
{
    /**
     * The private key pem-file path.
     *
     * @var string
     */
    protected $privateKey;

    /**
     * Password for the private key if set.
     *
     * @var string
     */
    protected $privateKeyPassword;

    /**
     * The public key pem-file path.
     *
     * @var string
     */
    protected $certificate;

    /**
     * The id given by PayPal to the uploaded public key.
     *
     * @var string
     */
    protected $certificateId;

    /**
     * The file path of the certificate downloaded from Paypal
     *
     * @var string.
     */
    protected $paypalCertificate;

    /**
     * The PayPal environment (sandbox or production) as full URL.
     *
     * @var string
     */
    protected $environment;

    /**
     * The PayPal user name.
     *
     * @var string
     */
    protected $userName;

    /**
     * The url that should be used by paypal to send IPN messages.
     *
     * @var string
     */
    protected $ipnUrl;

    public function __construct(array $config)
    {
        $this->setByArray($config);
    }

    public function setPrivateKey($path)
    {
        $this->privateKey = $this->checkPath($path);
        return $this;
    }

    public function setPrivateKeyPassword($password)
    {
        $this->privateKeyPassword = $password;
    }

    public function setCertificate($path)
    {
        $this->certificate = $this->checkPath($path);
        return $this;
    }

    public function setCertificateId($id)
    {
        $this->certificateId = $id;
        return $this;
    }

    public function setPaypalCertificate($path)
    {
        $this->paypalCertificate = $this->checkPath($path);
        return $this;
    }

    public function setEnvironment($env)
    {
        $this->environment = $env;
        return $this;
    }

    public function setUserName($userName)
    {
        $this->userName = $userName;
        return $this;
    }

    public function setIpnUrl($url)
    {
        $this->ipnUrl = $url;
        return $this;
    }

    public function createEncryptedButton(array $params, $image)
    {
        $params['cert_id'] = $this->certificateId;
        $params['business'] = $this->userName;
        if (!array_key_exists('notify_url', $params) and $this->ipnUrl) {
            $params['notify_url'] = $this->ipnUrl;
        }

        $button = \EWPServices::encryptButton(
            $params,
			$this->certificate,
			$this->privateKey,
			$this->privateKeyPassword,
			$this->paypalCertificate,
			$this->environment,
			$image
        );
        return $button;
    }

    protected function setByArray(array $array)
    {
        foreach ($array as $key => $value) {
            $setter = 'set' . ucfirst($key);
            if (method_exists($this, $setter)) {
                $this->$setter($value);
            }
        }
        return $this;
    }

    protected function checkPath($path)
    {
        if (!is_readable($path)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Certificate path %s does not exist or is not readable.',
                    $path
                )
            );
        }
        return realpath($path);
    }

}