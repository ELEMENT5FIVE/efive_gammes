<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once _PS_MODULE_DIR_ . 'efive_gammes/classes/Gamme.php';
require_once _PS_MODULE_DIR_ . 'efive_gammes/classes/Specification.php';

class Efive_Gammes extends Module
{

    public function __construct()
    {
        $this->name = 'efive_gammes';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Valentin HUARD';
        $this->need_instance = 1;

        $this->bootstrap = true;
        parent::__construct();

        Shop::addTableAssociation('gamme', ['type' => 'shop']);
        Shop::addTableAssociation('specification', ['type' => 'shop']);

        $this->displayName = $this->l('Ranges of products for Elementfive');
        $this->description = $this->l('Description of the module visible in the BO');
    }

    public function install()
    {
        if ($this->_installSql() && parent::install() && $this->_installTab() && $this->registerHook('displayAdminProductsMainStepLeftColumnMiddle')) {
            return true;
        }

        return false;
    }

    public function uninstall()
    {
        if($this->_uninstallSql() && $this->_uninstallTab() && parent::uninstall()) {
            return true;
        }

        return false;
    }

    public function _installSql() 
    {
        include dirname(__FILE__) . '/sql/install.php';

        return true;
    }

    public function _uninstallSql() 
    {
        include dirname(__FILE__) . '/sql/uninstall.php';

        return true;
    }

    /**
     * Install the tab in the BO
     * under the "Catalog" menu
     * 
     * @return bool
     */
    public function _installTab()
    {
        $tabGamme = new Tab();
        $tabGamme->active = 1;
        $tabGamme->class_name = 'AdminGamme';
        $tabGamme->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tabGamme->name[$lang['id_lang']] = 'Gammes';
        }
        $tabGamme->id_parent = (int) Tab::getIdFromClassName('IMPROVE');
        $tabGamme->module = $this->name;
        $tabGamme->icon = 'grid_on';

        $tabSpecification = new Tab();
        $tabSpecification->active = 1;
        $tabSpecification->class_name = 'AdminSpecification';
        $tabSpecification->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tabSpecification->name[$lang['id_lang']] = 'Specifications';
        }
        $tabSpecification->id_parent = (int) Tab::getIdFromClassName('IMPROVE');
        $tabSpecification->module = $this->name;
        $tabSpecification->icon = 'tune';

        return $tabGamme->add() && $tabSpecification->add();
    }

    /**
     * Uninstall the tab in the BO
     * under the "Catalog" menu
     * 
     * @return bool
     */
    public function _uninstallTab()
    {
        $idTabGamme = (int) Tab::getIdFromClassName('AdminGamme');
        $idTabSpecification = (int) Tab::getIdFromClassName('AdminSpecification');

        if ($idTabGamme && $idTabSpecification) {
            $tabGamme = new Tab($idTabGamme);
            $tabSpecification = new Tab($idTabSpecification);

            return $tabGamme->delete() && $tabSpecification->delete();
        }

        return false;
    }

    /**
     * Affichage des informations supplémentaires sur la fiche produit
     * 
     * @param type $params
     * @return type
     */
    public function hookDisplayAdminProductsMainStepLeftColumnMiddle($params) {
        $product = new Product($params['id_product']);
        $languages = Language::getLanguages();
        $listGammes = Gamme::getGammes($this->context->language->id, $this->context->shop->id);
        $actualGamme = Gamme::getGammeById($product->id_gamme);

        $listSpecification = Specification::getSpecifications($this->context->language->id, $this->context->shop->id);
        $actualSpecification = Specification::getSpecificationById($product->id_specification);

        $this->context->smarty->assign ( array (
                'listGamme' => $listGammes,
                'gamme' => $actualGamme,
                'id_gamme' => $product->id_gamme,
                'listSpecification' => $listSpecification,
                'specification' => $actualSpecification,
                'id_specification' => $product->id_specification,
                'languages' => $languages,
                'default_language' => $this->context->employee->id_lang,
                'id_product' => $params['id_product']
            )
        );
        
        return $this->display(__FILE__, 'views/templates/hook/extrafields.tpl');
    }

}