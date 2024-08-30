<?php

class Specification extends ObjectModel
{

    /**
     * Identifier of specification
     * 
     * @var int
     */
    public $id_specification;

    /**
     * Name of specification
     * 
     * @var string
     */
    public $name;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = [
        'table' => 'specification',
        'primary' => 'id_specification',
        'multilang' => true,
        'multilang_shop' => true,
        'fields' => [
            'id_specification' => ['type' => self::TYPE_NOTHING, 'validate' => 'isUnsignedInt'],
            'name' => ['type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isString', 'required' => true, 'size' => 255],
        ],
    ];

    /**
     * Return all specification
     * 
     * @return array
     */
    /*
     * Get a list of specification objects
     *
     * @param int $id_lang ID of the language to get the specification names in (default: current language)
     * @param bool $active Whether to only retrieve active specification (default: true)
     *
     * @return array Array of Specification objects
     */
    public static function getSpecifications($id_lang = false, $id_shop = false)
    {
        $specifications = array();

        $sql = 'SELECT g.`id_specification`, gl.`name`
        FROM `' . _DB_PREFIX_ . 'specification` g
        ' . Shop::addSqlAssociation('specification', 'g') . '
        LEFT JOIN `' . _DB_PREFIX_ . 'specification_lang` gl ON (g.`id_specification` = gl.`id_specification`' . Shop::addSqlRestrictionOnLang('gl') . ')
        LEFT JOIN `' . _DB_PREFIX_ . 'specification_shop` gs ON (g.`id_specification` = gs.`id_specification`' . Shop::addSqlRestrictionOnLang('gs') . ')
        WHERE 1
        ' . ($id_lang ? 'AND gl.`id_lang` = ' . (int)$id_lang : '') . '
        ' . ($id_shop ? 'AND gs.`id_shop` = ' . (int)$id_shop : '') . '
        ORDER BY g.`id_specification` ASC';

        $results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        foreach ($results as $row) {
            $specifications[] = array(
                'id_specification' => $row['id_specification'],
                'name' => $row['name'],
                'lang' => $id_lang,
            );
        }

        return $specifications;
    }

    /**
     * Return the specification name using the ID
     * 
     * @param int $id_specification
     * 
     * @return string
     */
    protected static $cache_name = [];

    public static function getSpecificationById($idSpecification, $idLang = null)
    {
        if (!isset(self::$cache_name[$idSpecification])) {
            self::$cache_name[$idSpecification] = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('
			SELECT `name` FROM `' . _DB_PREFIX_ . 'specification_lang` WHERE `id_specification` = ' . (int) $idSpecification
                . ' AND `id_lang` = ' . (int) ($idLang ? $idLang : Context::getContext()->language->id)
        );
        }

        return self::$cache_name[$idSpecification];
    }


    /**
     * Return the specification ID By Shop ID
     * 
     * @param int $shopId
     * 
     * @return bool|int
     */
    public static function getSpecificationIdByShop($shopId)
    {
        $sql = 'SELECT g.`id_specification` FROM ' . _DB_PREFIX_ . 'specification g
                LEFT JOIN ' . _DB_PREFIX_ . 'specification_shop gs ON gs.`id_specification` = g.`id_specification`
                WHERE gs.`id_shop` = ' . (int) $shopId;

        if ($result = Db::getInstance()->executeS($sql)) {
            return (int) reset($result)['id_specification'];
        }

        return false;
    }
}
