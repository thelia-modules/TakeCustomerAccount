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

namespace TakeCustomerAccount;

use Symfony\Component\DependencyInjection\Loader\Configurator\ServicesConfigurator;
use Thelia\Core\Template\TemplateDefinition;
use Thelia\Module\BaseModule;

class TakeCustomerAccount extends BaseModule
{
    public const MODULE_DOMAIN = "takecustomeraccount";

    /**
     * {@inheritdoc}
     */
    public function getHooks(): array
    {
        return [
            [
                "type" => TemplateDefinition::BACK_OFFICE,
                "code" => "take-customer-account.form",
                "title" => array(
                    "fr_FR" => "Module Take Customer Account, form",
                    "en_US" => "Module Take Customer Account, form",
                ),
                "description" => array(
                    "fr_FR" => "En haut du formulaire",
                    "en_US" => "Top of form",
                ),
                "active" => true
            ]
        ];
    }

    public static function configureServices(ServicesConfigurator $servicesConfigurator): void
    {
        $servicesConfigurator->load(self::getModuleCode().'\\', __DIR__)
            ->exclude([__DIR__.'/I18n/*', __DIR__.'/Config/**/*.php', __DIR__.'/Tests/*', __DIR__.'/TakeCustomerAccount.php'])
            ->autowire(true)
            ->autoconfigure(true);
    }
}
