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

use TakeCustomerAccount\Event\TakeCustomerAccountEvent;
use TakeCustomerAccount\Event\TakeCustomerAccountEvents;
use TakeCustomerAccount\TakeCustomerAccount;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\HttpKernel\Exception\RedirectException;
use Thelia\Core\Security\AccessManager;
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
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function takeAction($customer_id)
    {
        if (null !== $response = $this->checkAuth(array(), 'TakeCustomerAccount', AccessManager::VIEW)) {
            return $response;
        }

        $form = $this->createForm('take_customer_account');

        try {
            if (null !== $customer = CustomerQuery::create()->findPk($customer_id)) {
                $this->validateForm($form);

                $this->dispatch(
                    TakeCustomerAccountEvents::TAKE_CUSTOMER_ACCOUNT,
                    new TakeCustomerAccountEvent($customer)
                );
            } else {
                throw new \Exception($this->getTranslator()->trans(
                    "Customer not found",
                    [],
                    TakeCustomerAccount::MODULE_DOMAIN
                ));
            }

            $this->setCurrentRouter('router.front');
            return $this->generateRedirectFromRoute('customer.home', [], [], 'router.front');
        } catch (RedirectException $e) {
            return $this->generateRedirect($e->getUrl(), $e->getCode());
        } catch (\Exception $e) {
            $form->setErrorMessage($e->getMessage());

            $this->getParserContext()->addForm($form);

            $this->setCurrentRouter('router.admin');
            return $this->generateRedirectFromRoute(
                'admin.customer.update.view',
                ['customer_id' => $customer_id]
            );
        }
    }
}
