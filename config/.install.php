<?php
/**
 * Этот файл является частью виджета веб-приложения GearMagic.
 * 
 * Файл конфигурации установки виджета.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

return [
    'use'         => FRONTEND,
    'id'          => 'gm.wd.breadcrumbs',
    'category'    => 'navigation',
    'name'        => 'Breadcrumbs',
    'description' => 'Navigation chain of elements on the site',
    'namespace'   => 'Gm\Widget\Breadcrumbs',
    'path'        => '/gm/gm.wd.breadcrumbs',
    'locales'     => ['ru_RU', 'en_GB'],
    'events'      => [],
    'required'    => [
        ['php', 'version' => '8.2'],
        ['app', 'code' => 'GM CMS']
    ]
];
