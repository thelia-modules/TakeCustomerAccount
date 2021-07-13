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

namespace TakeCustomerAccount\Form;

use Thelia\Form\BaseForm;

/**
 * Class TakeCustomerAccountForm
 * @package TakeCustomerAccount\Form
 * @author Gilles Bourgeat <gbourgeat@openstudio.fr>
 */
class TakeCustomerAccountForm extends BaseForm
{
    /**
     * @return string the name of you form. This name must be unique
     */
    public static function getName()
    {
        return 'take_customer_account';
    }

    /**
     *
     * in this function you add all the fields you need for your Form.
     * Form this you have to call add method on $this->formBuilder attribute :
     *
     */
    protected function buildForm()
    {
    }
}
