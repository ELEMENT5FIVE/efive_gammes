<?php

/**
 * 2007-2016 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author    Valentin HUARD
 *  @copyright 2023 Valentin HUARD
 */

include_once _PS_MODULE_DIR_ . 'efive_gammes/classes/Gamme.php';
include_once _PS_MODULE_DIR_ . 'efive_gammes/classes/Specification.php';

class Product extends ProductCore {
    

    /**
     * @var int
     */
    public $id_specification;

    /**
     * @var int
     */
    public $id_gamme;

    /**
     * @var string
     */
    public $gamme;
    
    public function __construct($id_product = null, $full = false, $id_lang = null, $id_shop = null, \Context $context = null) {

        //Définition des nouveaux champs
        self::$definition['fields']['id_gamme'] = ['type' => self::TYPE_INT, 'validate' => 'isUnsignedId'];
        self::$definition['fields']['id_specification'] = ['type' => self::TYPE_INT, 'validate' => 'isUnsignedId'];

        $this->gamme = Gamme::getGammeById((int) $this->id_gamme);

        parent::__construct($id_product, $full, $id_lang, $id_shop, $context);
    }
}