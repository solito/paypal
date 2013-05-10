<?php
namespace PayPal\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Paypal\Controller;
use Zend\Log\LoggerAwareInterface;
use Zend\Log\LoggerAwareTrait;
use Zend\Log\LoggerInterface;
use PayPal\IpnException;


/**
 * Controller that deals with IPN messages. 
 * 
 * @see https://cms.paypal.com/uk/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_html_IPNandPDTVariables#id08CTB0S055Z
 * @author Thomas Lhotta
 *
 */
class IpnController extends AbstractRestfulController implements LoggerAwareInterface
{

	use LoggerAwareTrait;
	
	/**
     * Returns a logger instance.
     * 
     * @return Zend\Log\LoggerInterface
	 */
	public function getLogger()
	{
	    if (!$this->logger instanceof LoggerInterface) {
	        $this->logger = $this->getServiceLocator()->get('Zend\Log\Logger');
	    }
	    
	    return $this->logger;
	}
	
    public function getList()
    {
        
        $response = $this->getResponse();
        $response->setStatusCode(404);
        $response->send();
        return array();
        /*
        $ipn = array(
            "txn_type" =>"subscr_payment",
            'subscr_id' => 'someId',
            "txn_id"=>"9W87459183924521Y",
            "mc_gross"=>"6.99",
            "protection_eligibility"=>"Ineligible",
            "address_status"=>"unconfirmed",
            "payer_id"=>"HHZFGP35L3GS8",
            "tax"=>"0.00",
            "address_street"=>"ESpachstr. 1",
            "payment_date"=>"11=>02=>37 Oct 04, 2012 PDT",
            "payment_status"=>"Pending",
            //"payment_status"=>"Refunded",
            "charset"=>"windows-1252",
            "address_zip"=>"79111",
            "first_name"=>"Thomas",
            "address_country_code"=>"DE",
            "address_name"=>"Thomas Lhotta",
            "notify_version"=>"3.7",
            "custom"=>"803-1",
            "payer_status"=>"verified",
            "business"=>"office@dailyhealthcoach.net",
            "address_country"=>"Germany",
            "address_city"=>"Freiburg",
            "quantity"=>"1",
            "verify_sign"=>"AiGz.4HIRlBadusZbp9-iF8lvXjKAmyb1DpRM0UPcOKg17uaf8evG5BA",
            "payer_email"=>"lhotta_1345635459_pre@dailyhealthcoach.net",
            "payment_type"=>"instant",
            "last_name"=>"Lhotta",
            "address_state"=>"Empty",
            "receiver_email"=>"office@dailyhealthcoach.net",
            "pending_reason"=>"unilateral",
            "item_name"=>"3 Monate",
            "mc_currency"=>"EUR",
            "item_number"=>"",
            "residence_country"=>"DE",
            "test_ipn"=>"1",
            "handling_amount"=>"0.00",
            "transaction_subject"=>"1-1",
            "payment_gross"=>"",
            "shipping"=>"0.00",
            "ipn_track_id"=>"ad0a441356cbb"
        );

        
        $return = $this->create($ipn);

        return $return;
        */
    }

    public function get($id)
    {
        $response = $this->getResponse();
        $response->setStatusCode(404);
        $response->send();
        return array();
    }

    public function create($data)
    {
        $response = $this->getResponse();
        $response->setStatusCode(200);
        $viewModel = new JsonModel();
        /**
         * Verify and validate the IPN message.
         */

        // Send response to signal paypal that the IPN has been received.
        $response->send();
        
        try {
            /* @var $ipn \PayPal\Service\IPN */
            $ipn = $this->getServiceLocator()->get('PayPal\Service\IPN');
            
        	// Check if the message has originated form PayPal.
        	// If not log.
        	if ($ipn->verifyIPNMessage($data) !== true) {
        		throw new IpnException('Unverified IPN message received');
        	}
        	
        	// Check if the payment was directed to the right receiver.
        	if ($ipn->verifyReceiver($data) !== true) {
        		throw new IpnException('Received IPN with wrong receiver email.');
        	}
        	
        	
        	$this->getEventManager()->trigger(
        			'processIpn',
        			$this,
        			array('data' => $data)
        	);
        	
        } catch (IpnException $e) {
        	$this->getLogger()->crit($e->getMessage(), $data);
        }
        
        return $viewModel;
    }

    public function update($id, $data)
    {
        $response = $this->getResponse();
        $response->setStatusCode(404);
        $response->send();
    }

    public function delete($id)
    {
        $response = $this->getResponse();
        $response->setStatusCode(404);
        $response->send();
    }
}
