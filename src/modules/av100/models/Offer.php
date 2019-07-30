<?php

namespace modules\av100\models;

class Offer extends \base\core\Model {
    
    public static $create_q = " 
            DROP TABLE IF EXISTS `av100_offers`;
            CREATE TABLE IF NOT EXISTS `av100_offers` (
                `id` int(11) not null primary key AUTO_INCREMENT,
                `avid` int(11) DEFAULT NULL,
                `phone` varchar(255) DEFAULT NULL,
                `phone2` varchar(255) DEFAULT NULL,
                `email` varchar(255) DEFAULT NULL,
                `name` varchar(255) DEFAULT NULL,
                `year` int(11) DEFAULT NULL,
                `url` varchar(255) DEFAULT NULL,
                `marka` varchar(255) DEFAULT NULL,
                `model` varchar(255) DEFAULT NULL,
                `credate` datetime DEFAULT NULL,
                `price` int(11) DEFAULT NULL,
                `source` int(11) DEFAULT NULL,
                `city` varchar(255) DEFAULT NULL,
                `descr` text,
                `status` int(1) DEFAULT NULL,
                `comments` text,
                `delta` int(11) DEFAULT NULL,
                `isalive` int(11) DEFAULT '0'
            ) CHARACTER SET utf8 COLLATE utf8_general_ci;";
    
    public $fields = [
        'id' => null,
        'avid' => null,
        'year' => null,
        'price' => null,
        'source' => null,
        'url' => null,
        'marka' => null,
        'model' => null,
        'city' => null,
        'descr' => null,
        'delta' => null,
        'status' => null,
        'credate' => null,
        'phone' => null,
        'name' => null,
    ];

    public static function table_name() {
        return 'av100_offers';
    }
}
