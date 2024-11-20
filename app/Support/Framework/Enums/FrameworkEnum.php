<?php

namespace App\Support\Framework\Enums;

enum FrameworkEnum: string
{
    case LARAVEL = 'laravel/framework';
    case LUMEN = 'laravel/lumen-framework';
    case FILAMENT = 'laravel-filament/filament';

    case SYMFONY = 'symfony/symfony';
    case ZEND = 'zendframework/zendframework';
    case CODEIGNITER = 'codeigniter/framework';
    case YII = 'yiisoft/yii2';
    case YII1 = 'yiisoft/yii';
    case SLIM = 'slim/slim';
    case CAKEPHP = 'cakephp/cakephp';
    case PHALCON = 'phalcon/cphalcon';
    case FUEL = 'fuel/fuel';
    case FATFREE = 'bcosca/fatfree';
    case PHP = 'php';
    case WORDPRESS = 'wordpress/wordpress';
    case DRUPAL = 'drupal/drupal';
    case JOOMLA = 'joomla/joomla-cms';
    case MAGENTO = 'magento/magento2';
    case OPENCART = 'opencart/opencart';
    case PRESTASHOP = 'prestashop/prestashop';
    case SHOPIFY = 'shopify/shopify';
    case GRAV = 'getgrav/grav';
    case STATAMIC = 'statamic/cms';
    case LARAVEL_ZERO = 'laravel-zero/framework';
    case LARAVEL_JETSTREAM = 'laravel/jetstream';
    case BEDROCK = 'roots/bedrock';
    case BOLT = 'bolt/bolt';
    case LARAVEL_NOVA = 'laravel/nova';
    case KATANA = 'katanacms/katana';
    case LARAVEL_LUMEN = 'laravel/lumen';


    public function label(): ?string
    {
        return match ($this) {
            self::LARAVEL => 'Laravel',
            self::LUMEN => 'Lumen',
            self::FILAMENT => 'Filament',
            self::SYMFONY => 'Symfony',
            self::ZEND => 'Zend Framework',
            self::CODEIGNITER => 'CodeIgniter',
            self::YII => 'Yii 2',
            self::YII1 => 'Yii 1',
            self::SLIM => 'Slim',
            self::CAKEPHP => 'CakePHP',
            self::PHALCON => 'Phalcon',
            self::FUEL => 'FuelPHP',
            self::FATFREE => 'Fat-Free Framework',
            self::PHP => 'Native PHP',
            self::WORDPRESS => 'WordPress',
            self::DRUPAL => 'Drupal',
            self::JOOMLA => 'Joomla',
            self::MAGENTO => 'Magento',
            self::OPENCART => 'OpenCart',
            self::PRESTASHOP => 'PrestaShop',
            self::SHOPIFY => 'Shopify',
            self::GRAV => 'Grav',
            self::STATAMIC => 'Statamic',
            self::LARAVEL_ZERO => 'Laravel Zero',
            self::LARAVEL_JETSTREAM => 'Laravel Jetstream',
            self::BEDROCK => 'Bedrock',
            self::BOLT => 'Bolt',
            self::LARAVEL_NOVA => 'Laravel Nova',
            self::KATANA => 'Katana',
            self::LARAVEL_LUMEN => 'Laravel Lumen',
        };
    }

}
