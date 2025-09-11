<?php

declare(strict_types=1);

/*
 * This file is part of the Thelia package.
 * http://www.thelia.net
 *
 * (c) OpenStudio <info@thelia.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/*      Copyright (c) OpenStudio */
/*      email : dev@thelia.net */
/*      web : http://www.thelia.net */

/*      For the full copyright and license information, please view the LICENSE.txt */
/*      file that was distributed with this source code. */

namespace TakeCustomerAccount\EventListener;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use TakeCustomerAccount\Event\TakeCustomerAccountEvent;
use TakeCustomerAccount\Event\TakeCustomerAccountEvents;
use TakeCustomerAccount\TakeCustomerAccount;
use Thelia\Core\Event\Cart\CartCreateEvent;
use Thelia\Core\Event\Customer\CustomerLoginEvent;
use Thelia\Core\Event\DefaultActionEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Core\Security\AccessManager;
use Thelia\Core\Security\SecurityContext;
use Thelia\Model\AdminLog;

/**
 * Class TakeCustomerAccountListener.
 *
 * @author Gilles Bourgeat <gbourgeat@openstudio.fr>
 */
class TakeCustomerAccountListener implements EventSubscriberInterface
{
    public function __construct(
        protected EventDispatcherInterface $eventDispatcher,
        protected SecurityContext $securityContext,
        protected RequestStack $requestStack,
    ) {
    }

    public function take(TakeCustomerAccountEvent $event): void
    {
        $this->eventDispatcher->dispatch(new DefaultActionEvent(), TheliaEvents::CUSTOMER_LOGOUT);

        $this->eventDispatcher->dispatch(
            new CustomerLoginEvent($event->getCustomer()),
            TheliaEvents::CUSTOMER_LOGIN
        );

        $newCartEvent = new CartCreateEvent();
        $this->eventDispatcher->dispatch($newCartEvent, TheliaEvents::CART_CREATE_NEW);

        AdminLog::append(
            TakeCustomerAccount::MODULE_DOMAIN,
            AccessManager::VIEW,
            'Took control of the customer account "'.$event->getCustomer()->getId().'"',
            $this->requestStack->getCurrentRequest(),
            $this->securityContext->getAdminUser()
        );
    }

    public static function getSubscribedEvents(): array
    {
        return [
            TakeCustomerAccountEvents::TAKE_CUSTOMER_ACCOUNT => ['take', 128],
        ];
    }
}
