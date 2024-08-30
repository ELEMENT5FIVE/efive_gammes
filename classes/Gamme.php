<?php

class Gamme extends ObjectModel
{

    /**
     * Identifier of gamme
     * 
     * @var int
     */
    public $id_gamme;

    /**
     * Name of gamme
     * 
     * @var string
     */
    public $name;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = [
        'table' => 'gamme',
        'primary' => 'id_gamme',
        'multilang' => true,
        'multilang_shop' => true,
        'fields' => [
            'id_gamme' => ['type' => self::TYPE_NOTHING, 'validate' => 'isUnsignedInt'],
            'name' => ['type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isString', 'required' => true, 'size' => 255],
        ],
    ];

    /**
     * Return all gamme
     * 
     * @return array
     */
    /*
     * Get a list of gamme objects
     *
     * @param int $id_lang ID of the language to get the gamme names in (default: current language)
     * @param bool $active Whether to only retrieve active gamme (default: true)
     *
     * @return array Array of Gamme objects
     */
    public static function getGammes($id_lang = false, $id_shop = false)
    {
        $gammes = array();

        $sql = 'SELECT g.`id_gamme`, gl.`name`
        FROM `' . _DB_PREFIX_ . 'gamme` g
        ' . Shop::addSqlAssociation('gamme', 'g') . '
        LEFT JOIN `' . _DB_PREFIX_ . 'gamme_lang` gl ON (g.`id_gamme` = gl.`id_gamme`' . Shop::addSqlRestrictionOnLang('gl') . ')
        LEFT JOIN `' . _DB_PREFIX_ . 'gamme_shop` gs ON (g.`id_gamme` = gs.`id_gamme`' . Shop::addSqlRestrictionOnLang('gs') . ')
        WHERE 1
        ' . ($id_lang ? 'AND gl.`id_lang` = ' . (int)$id_lang : '') . '
        ' . ($id_shop ? 'AND gs.`id_shop` = ' . (int)$id_shop : '') . '
        ORDER BY g.`id_gamme` ASC';

        $results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        foreach ($results as $row) {
            $gammes[] = array(
                'id_gamme' => $row['id_gamme'],
                'name' => $row['name'],
                'lang' => $id_lang,
            );
        }

        return $gammes;
    }

    /**
     * Return the gamme name using the ID
     * 
     * @param int $id_gamme
     * 
     * @return string
     */
    protected static $cache_name = [];

    public static function getGammeById($idGamme, $idLang = null)
    {
        if (!isset(self::$cache_name[$idGamme])) {
            self::$cache_name[$idGamme] = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('
			SELECT `name` FROM `' . _DB_PREFIX_ . 'gamme_lang` WHERE `id_gamme` = ' . (int) $idGamme
                . ' AND `id_lang` = ' . (int) ($idLang ? $idLang : Context::getContext()->language->id)
        );
        }

        return self::$cache_name[$idGamme];
    }


    /**
     * Return the gamme ID By Shop ID
     * 
     * @param int $shopId
     * 
     * @return bool|int
     */
    public static function getGammeIdByShop($shopId)
    {
        $sql = 'SELECT g.`id_gamme` FROM ' . _DB_PREFIX_ . 'gamme g
                LEFT JOIN ' . _DB_PREFIX_ . 'gamme_shop gs ON gs.`id_gamme` = g.`id_gamme`
                WHERE gs.`id_shop` = ' . (int) $shopId;

        if ($result = Db::getInstance()->executeS($sql)) {
            return (int) reset($result)['id_gamme'];
        }

        return false;
    }
}
