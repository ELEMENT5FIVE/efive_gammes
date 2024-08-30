<?php

$sql = array();

$sql[] = "ALTER TABLE " . _DB_PREFIX_ . "product DROP id_gamme";
$sql[] = "ALTER TABLE " . _DB_PREFIX_ . "product DROP id_specification";

$sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'gamme`';
$sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'gamme_lang`';
$sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'gamme_shop`';

$sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'specification`';
$sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'specification_lang`';
$sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'specification_shop`';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}