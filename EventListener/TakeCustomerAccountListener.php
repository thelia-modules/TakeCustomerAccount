<?php
/*************************************************************************************/
/*      This file is part of the module FeatureType                                */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace TakeCustomerAccount\EventListener;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use TakeCustomerAccount\Event\TakeCustomerAccountEvent;
use TakeCustomerAccount\Event\TakeCustomerAccountEvents;
use TakeCustomerAccount\TakeCustomerAccount;
use Thelia\Core\Event\Cart\CartCreateEvent;
use Thelia\Core\Event\Customer\CustomerEvent;
use Thelia\Core\Event\Customer\CustomerLoginEvent;
use Thelia\Core\Event\DefaultActionEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Core\HttpFoundation\Request;
use Thelia\Core\Security\AccessManager;
use Thelia\Core\Security\SecurityContext;
use Thelia\Model\AdminLog;

/**
 * Class TakeCustomerAccountListener
 * @package TakeCustomerAccount\EventListener
 * @author Gilles Bourgeat <gbourgeat@openstudio.fr>
 */
class TakeCustomerAccountListener implements EventSubscriberInterface
{
    /** @var EventDispatcherInterface */
    protected $eventDispatcher;

    /** @var SecurityContext */
    protected $securityContext;

    /** @var Request */
    protected $request;

    /**
     * @param EventDispatcherInterface $eventDispatcher
     * @param SecurityContext $securityContext
     * @param Request $request
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        SecurityContext $securityContext,
        Request $request
    ) {
        $this->eventDispatcher = $eventDispatcher;

        $this->securityContext = $securityContext;

        $this->request = $request;
    }

    /**
     * @param TakeCustomerAccountEvent $event
     */
    public function take(TakeCustomerAccountEvent $event)
    {
        $this->eventDispatcher->dispatch( new DefaultActionEvent(),TheliaEvents::CUSTOMER_LOGOUT);

        $this->eventDispatcher->dispatch(
            new CustomerLoginEvent($event->getCustomer()),
            TheliaEvents::CUSTOMER_LOGIN
        );

        $newCartEvent = new CartCreateEvent();
        $this->eventDispatcher->dispatch($newCartEvent,TheliaEvents::CART_CREATE_NEW);

        AdminLog::append(
            TakeCustomerAccount::MODULE_DOMAIN,
            AccessManager::VIEW,
            'Took control of the customer account "' . $event->getCustomer()->getId() . '"',
            $this->request,
            $this->securityContext->getAdminUser()
        );
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            TakeCustomerAccountEvents::TAKE_CUSTOMER_ACCOUNT => ['take', 128]
        );
    }
}
