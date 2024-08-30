<?php

/**
 * 2022 aPartner.Top AND programist.top
 *
 * NOTICE OF LICENSE
 *
 * All right is reserved,
 * Please go through this link for complete license : https://apartner.top/license.html
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize this module for your
 * needs please refer to https://apartner.top/customisation-guidelines/ for more information.
 *
 *  @author    Valentin HUARD
 *  @copyright 2022 aPartner.Top AND programist.top
 *  @license   https://apartner.top/license.html
 */

require_once _PS_MODULE_DIR_ . 'efive_gammes/classes/Specification.php';

class AdminSpecificationController extends ModuleAdminController
{

    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = Specification::$definition['table'];
        $this->className = Specification::class;
        $this->identifier = Specification::$definition['primary'];
        $this->lang = true;

        $this->default_form_language = (int) Configuration::get('PS_LANG_DEFAULT');
        $this->allow_employee_form_lang = (int) Configuration::get('PS_LANG_DEFAULT');

        parent::__construct();

        $this->fields_list = [
            'id_specification' => [
                'title' => $this->l('ID'),
                'align' => 'center',
                'class' => 'fixed-width-xs',
            ],
            'name' => [
                'title' => $this->l('Name'),
                'align' => 'center',
                'class' => 'fixed-width-xs',
            ],
        ];

        if (Shop::isFeatureActive()) {
            $this->fields_list['id_shop'] = [
                'title' => $this->l('Shop association'),
                'align' => 'center',
                'class' => 'fixed-width-xs',
                'callback' => 'getShopName',
            ];
        }

        $this->addRowAction('edit');
        $this->addRowAction('delete');

        $this->bulk_actions = [
            'delete' => [
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?'),
                'icon' => 'icon-trash',
            ],
        ];
    }

    public function renderForm()
    {
        $this->fields_form = [
            'legend' => [
                'title' => $this->trans('Specification', [], 'Modules.Elementfive.Admin'),
                'icon' => 'icon-cogs',
            ],
            'input' => [
                'id_specification' => [
                    'type' => 'hidden',
                    'name' => 'id_specification',
                ],
                'name' => [
                    'type' => 'text',
                    'lang' => true,
                    'name' => 'name',
                    'label' => $this->trans('Name block', [], 'Modules.Elementfive.Admin'),
                ],
                'shop' => [
                    'type' => 'shop',
                    'name' => 'checkBoxShopAsso_theme',
                    'label' => $this->trans('Shop association', [], 'Admin.Global'),
                ],
            ],
            'submit' => [
                'title' => $this->trans('Save', [], 'Admin.Actions')
            ],
        ];

        $helper = new HelperForm();
        $helper->module = $this->module;
        $helper->name_controller = $this->className;
        $helper->identifier = $this->identifier;
        $helper->token = Tools::getAdminTokenLite('AdminSpecification');
        foreach (Language::getLanguages(false) as $lang) {
            $helper->languages[] = [
                'id_lang' => $lang['id_lang'],
                'iso_code' => $lang['iso_code'],
                'name' => $lang['name'],
                'is_default' => ($this->default_form_language == $lang['id_lang'] ? 1 : 0),
            ];
        }

        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->module->name;
        $helper->default_form_language = $this->default_form_language;
        $helper->allow_employee_form_lang = $this->allow_employee_form_lang;
        $helper->toolbar_scroll = true;
        $helper->submit_action = 'submitAddSpecification';

        $helper->fields_value = $this->getFormValues();

        $this->helper = $helper;

        return parent::renderForm();
    }

    public function getFormValues()
    {
        $fields_value = [];
        $idShop = (int) Context::getContext()->shop->id;
        $idSpecification = Specification::getSpecificationIdByShop($idShop);

        Shop::setContext(Shop::CONTEXT_SHOP, $idShop);
        $specification = new Specification((int) $idSpecification);

        $fields_value['id_specification'] = $idSpecification;
        $fields_value['name'] = $specification->name;

        return $fields_value;
    }

    public function getShopName($value, $tr)
    {
        $shop_id = (int) $value;
        $shop_name = Shop::getShop($shop_id)['name'];

        return $shop_name;
    }
}
