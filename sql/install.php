<?php

$sql = array();

$sql[] = "ALTER TABLE " . _DB_PREFIX_ . "product ADD id_gamme int UNSIGNED";
$sql[] = "ALTER TABLE " . _DB_PREFIX_ . "product ADD id_specification int UNSIGNED";

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'gamme` (
    `id_gamme` int UNSIGNED NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`id_gamme`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'gamme_lang` (
    `id_gamme` int UNSIGNED NOT NULL,
    `id_lang` int(10) UNSIGNED NOT NULL,
    `id_shop` int(10) UNSIGNED NOT NULL,
    `name` varchar(255) NOT NULL,
    PRIMARY KEY (`id_gamme`,`id_lang`, `id_shop`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'gamme_shop` (
    `id_gamme` int UNSIGNED NOT NULL,
    `id_shop` int(10) UNSIGNED NOT NULL,
    PRIMARY KEY (`id_gamme`,`id_shop`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'specification` (
    `id_specification` int UNSIGNED NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`id_specification`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'specification_lang` (
    `id_specification` int UNSIGNED NOT NULL,
    `id_lang` int(10) UNSIGNED NOT NULL,
    `id_shop` int(10) UNSIGNED NOT NULL,
    `name` varchar(255) NOT NULL,
    PRIMARY KEY (`id_specification`,`id_lang`, `id_shop`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'specification_shop` (
    `id_specification` int UNSIGNED NOT NULL,
    `id_shop` int(10) UNSIGNED NOT NULL,
    PRIMARY KEY (`id_specification`,`id_shop`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}