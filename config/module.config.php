<?php
return array(
  'paypal' => array(
          //Account credentials
          'Account' => array(
          	  // Examples	
              'acct1.UserName'  => 'platfo_1255077030_biz_api1.gmail.com',
              'acct1.Password'  => 1255077037,
              'acct1.Signature' => 'Abg0gYcQyxQvnf2HDJkKtA-p6pqhA1k-KTYE0Gcy1diujFio4io5Vqjf',
              'acct1.AppId'     => 'APP-80W284485P519543T',
              
              'acct2.UserName'  => 'suarumugam-biz_api1.paypal.com',
              'acct2.Password'  => 'N6WUWNG3AESQUSXB',
              'acct2.CertKey'   => 'KJAERUGBLVF6Y',
              'acct2.CertPath'  => 'config/cert_key.pem',
              'acct2.AppId'     => 'APP-5XV204960S3290106',
          ),
          //Connection Information
          'Http' => array(
              'http.ConnectionTimeOut'  => 30,
              'http.Retry'              => 5,
              'http.TrustAllConnection' => false,
              //http.Proxy
          ),
          //Service Configuration
          'Service' => array(
              'service.Binding'       => 'SOAP',
              'service.EndPoint'      => "https://api.sandbox.paypal.com/2.0/",
              'service.RedirectURL'   => "https://www.sandbox.paypal.com/webscr&cmd=",
              'service.DevCentralURL' => "https://developer.paypal.com",
              'service.Version'       => 78
          ),
          //Logging Information
          'Log' => array(
              'log.FileName'   => 'PayPal.log',
              'log.LogLevel'   => 'Debug',
              'log.LogEnabled' => true
         ),
         'wps_toolkit' => array(         
  		      'privateKey'             => '',
  		      'privateKeyPassword'     => '',
  		      'certificate'            => '',
  		      'certificateId'          => '',
  		      'paypalCertificate'      => '',
              'environment'            => '',
              'userName'               => '',                   
  		  ),
      )
);
