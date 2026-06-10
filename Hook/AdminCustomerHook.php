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

namespace TakeCustomerAccount\Hook;

use TakeCustomerAccount\Form\TakeCustomerAccountForm;
use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Form\TheliaFormFactory;
use Thelia\Core\Hook\BaseHook;
use Thelia\Core\Template\Parser\ParserResolver;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * Class AdminCustomerHook
 * @package TakeCustomerAccount\Hook
 * @author Gilles Bourgeat <gbourgeat@openstudio.fr>
 */
class AdminCustomerHook extends BaseHook
{
    public function __construct(
        private readonly TheliaFormFactory $formFactory,
        ?EventDispatcherInterface $dispatcher = null,
        ?ParserResolver $parserResolver = null,
    ) {
        parent::__construct($dispatcher, $parserResolver);
    }

    public static function getSubscribedHooks(): array
    {
        return [
            'customer.edit' => [
                ['type' => 'back', 'method' => 'onCustomerEdit'],
            ],
            'customer.edit-js' => [
                ['type' => 'back', 'method' => 'onCustomerEditJs'],
            ],
        ];
    }

    public function onCustomerEdit(HookRenderEvent $event): void
    {
        $event->add($this->render(
            'TakeCustomerAccount/customer-edit.html.twig',
            [
                'customer_id' => $event->getArgument('customer_id'),
                'take_account_form' => $this->formFactory->createForm(TakeCustomerAccountForm::getName())->getForm()->createView(),
            ]
        ));
    }

    public function onCustomerEditJs(HookRenderEvent $event): void
    {
        $event->add($this->render(
            'TakeCustomerAccount/customer-edit-js.html.twig',
            [
                'customer_id' => $event->getArgument('customer_id'),
            ]
        ));
    }
}
