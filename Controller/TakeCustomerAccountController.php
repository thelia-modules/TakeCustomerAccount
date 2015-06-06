<?php
/*************************************************************************************/
/*      This file is part of the module TakeCustomerAccount                          */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace TakeCustomerAccount\Controller;

use TakeCustomerAccount\TakeCustomerAccount;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\Event\Customer\CustomerLoginEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Core\Security\AccessManager;
use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Model\AdminLog;
use Thelia\Model\CustomerQuery;

/**
 * Class TakeCustomerAccountController
 * @package TakeCustomerAccount\Controller
 * @author Gilles Bourgeat <gbourgeat@openstudio.fr>
 */
class TakeCustomerAccountController extends BaseAdminController
{
    /**
     * @param int $customer_id
     * @return mixed|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function takeAction($customer_id)
    {
        if (null !== $response = $this->checkAuth(array(
                AdminResources::MODULE), 'TakeCustomerAccount', AccessManager::VIEW)) {
            return $response;
        }

        $form = $this->createForm('take_customer_account');

        $formValidate = $this->validateForm($form);

        if (null !== $customer = CustomerQuery::create()->findPk($customer_id)) {
            $this->dispatch(TheliaEvents::CUSTOMER_LOGOUT);

            $this->dispatch(TheliaEvents::CUSTOMER_LOGIN, new CustomerLoginEvent($customer));

            AdminLog::append(
                TakeCustomerAccount::MODULE_DOMAIN,
                AccessManager::VIEW,
                'Took control of the customer account "' . $customer->getId() . '"',
                $this->getRequest(),
                $this->getSecurityContext()->getAdminUser()
            );
        } else {
            throw new \Exception($this->getTranslator()->trans(
                "Customer not found",
                [],
                TakeCustomerAccount::MODULE_DOMAIN
            ));
        }

        if (null !== $formValidate->get('success_url')->getData()) {
            return $this->generateSuccessRedirect($form);
        }
        return $this->generateRedirect("/account");
    }
}
