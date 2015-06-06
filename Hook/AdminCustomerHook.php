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

use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;

/**
 * Class AdminCustomerHook
 * @package TakeCustomerAccount\Hook
 * @author Gilles Bourgeat <gbourgeat@openstudio.fr>
 */
class AdminCustomerHook extends BaseHook
{
    /**
     * @param HookRenderEvent $event
     */
    public function onCustomerEdit(HookRenderEvent $event)
    {
        $event->add($this->render(
            'take-customer-account/hook/customer-edit.html',
            array(
                'customer_id' => $event->getArgument('customer_id')
            )
        ));
    }

    /**
     * @param HookRenderEvent $event
     */
    public function onCustomerEditJs(HookRenderEvent $event)
    {
        $event->add($this->render(
            'take-customer-account/hook/customer-edit-js.html',
            array(
                'customer_id' => $event->getArgument('customer_id')
            )
        ));
    }
}
