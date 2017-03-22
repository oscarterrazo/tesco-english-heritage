<?php

namespace TescoRedemptionPortal\Controller;

use TescoRedemptionPortal\Interfaces\MVouchersInterface;
use TescoRedemptionPortal\Form;
use TescoRedemptionPortal\Exception\TokenCancelledException;
use TescoRedemptionPortal\Exception\TokenExpiredException;
use TescoRedemptionPortal\Exception\TokenNotRecognisedException;
use TescoRedemptionPortal\Exception\TokenSuspendedException;
use TescoRedemptionPortal\Exception\VoucherLockedException;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Model\ViewModel;

class TokenController extends AbstractActionController
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var MVouchersInterface
     */
    private $mVouchersService;

    function __construct(ServiceLocatorInterface $serviceLocator,
                         MVouchersInterface $mVouchersService)
    {
        $this->serviceLocator = $serviceLocator;
        $this->mVouchersService = $mVouchersService;

        $this->logger = $this->serviceLocator->get('Zend\Log');
    }

    public function verifyAction()
    {
        // Variable for any error
        $error = null;

        $form = new Form\VerifyForm();
        $form->get('submit')->setValue('Verify');


        $request = $this->getRequest();
        if ($request->isPost()) {

            $form->setInputFilter(new Form\VerifyFilter());
            $data = $request->getPost();

            $form->setData($data);

            $token = $data['token'];

            try {
                $result = $this->mVouchersService->verify($token);

                if ($result['status'] == 'ACTIVE') {
                    return $this->redirect()->toRoute('token',
                        array('controller'=> 'token',
                            'action' => 'userInfo',
                            'tokenId' => $token,
                            'value' => $result['value'],));
                }
            } catch (TokenSuspendedException $e) {
                $error = 'The token introduced has been suspended';
                $this->logger->err('Failed to verify: ' . $token . ' - ' . $e->getMessage());
            }
            catch (TokenCancelledException $e) {
                $error = 'The token introduced has been cancelled';
                $this->logger->err('Failed to verify: ' . $token . ' - ' . $e->getMessage());
            }
            catch (TokenExpiredException $e) {
                $error = 'The token introduced has expired';
                $this->logger->err('Failed to verify: ' . $token . ' - ' . $e->getMessage());
            }
            catch (VoucherLockedException $e) {
                $error = 'The voucher is currently locked, try again in a few minutes';
                $this->logger->err('Failed to verify: ' . $token . ' - ' . $e->getMessage());
            }
            catch (TokenNotRecognisedException $e) {
                $error = 'Token not found';
                $this->logger->err('Failed to verify: ' . $token . ' - ' . $e->getMessage());
            }
            catch (Exception $e) {
                $error = 'An unexpected error happened';
                $this->logger->err('Failed to verify: ' . $token . ' - ' . $e->getMessage());
            }
        }

        return new ViewModel(array(
            'form' => $form,
            'error' => $error
        ));
    }


    public function userInfoAction()
    {
        $tokenId = $this->params('tokenId');
        $value = $this->params('value');

        if ($tokenId == null || $value == null) {
            return $this->redirect()->toRoute('home');
        }

        // Variable for any error
        $error = null;

        //$form = new Form\VerifyForm();
        //$form->get('submit')->setValue('Verify');

        return new ViewModel(array(
            //'form' => $form,
            //'error' => $error
        ));
    }

    public function ajaxProcessUserInfoAction()
    {
        if (!$this->isAjaxPost()) {
            return $this->getResponse();
        }

        // Grab the request object
        $request = $this->getRequest();

        // Grab the input data
        $data = $request->getPost();
        $vouchers = $data['storage'];

    }

    private function isAjaxPost()
    {
        // Grab the request object
        $request = $this->getRequest();

        // Check this is an Ajax request
        if (!$request->isXmlHttpRequest()) {
            $this->getResponse()->setStatusCode(400);
            return false;
        }

        // Check if is a post request
        if (!$request->isPost()) {
            $this->getResponse()->setStatusCode(400);
            return false;
        }

        return true;
    }
}