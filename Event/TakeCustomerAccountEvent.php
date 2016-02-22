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

namespace TakeCustomerAccount\Event;

use Thelia\Core\Event\ActionEvent;
use Thelia\Model\Customer;

/**
 * Class TakeCustomerAccountEvent
 * @package TakeCustomerAccount\Event
 * @author Gilles Bourgeat <gbourgeat@openstudio.fr>
 */
class TakeCustomerAccountEvent extends ActionEvent
{
    /** @var Customer */
    protected $customer;

    /**
     * @param Customer $customer
     */
    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }

    /**
     * @return Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param Customer $customer
     * @return TakeCustomerAccountEvent
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;
        return $this;
    }
}
