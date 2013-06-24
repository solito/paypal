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
	 * IPN data to be processed.
	 * 
     * @var Array
	 */
	protected $data;
	
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
	
    public function create($data)
    {
        $em = $this->getEventManager()->getSharedManager();
        
        // Process IPN after rendering as paypal always expects a 200 response code.
        $em->attach('Zend\Mvc\Application', 'finish', array($this, 'processIPN'));
        
        $this->data = $data;
        
        $response = $this->getResponse();
        $response->setStatusCode(200);
        $viewModel = new JsonModel();
        return $viewModel;
    }

    /**
     * Process the IPN message. Requires the data property to be set.
     */
    public function processIPN()
    {
        $data = $this->data;
        
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
    }
    
    /*
     * Invalid methods.
     */
    
    protected function returnNotFound()
    {
        $response = $this->getResponse();
        $response->setStatusCode(404);
        return new JsonModel();
    }
    
    public function getList()
    {
        return $this->returnNotFound();
    }
    
    public function update($id, $data)
    {
        return $this->returnNotFound();
    }

    public function delete($id)
    {
        return $this->returnNotFound();
    }
    
    public function get($id)
    {
        return $this->returnNotFound();
    }
    
}
