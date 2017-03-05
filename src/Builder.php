<?php
/******************************************************************************
 * Copyright (c) 2017. Kitrix Team                                            *
 * Kitrix is open source project, available under MIT license.                *
 *                                                                            *
 * @author: Konstantin Perov <fe3dback@yandex.ru>                             *
 * Documentation:                                                             *
 * @see https://kitrix-org.github.io/docs                                     *
 *                                                                            *
 *                                                                            *
 ******************************************************************************/

namespace Kitrix\Builder;

use Kitrix\Entities\Asset;
use Kitrix\MVC\Admin\RouteFactory;
use Kitrix\Plugins\Plugin;

class Builder extends Plugin
{
    public function registerAssets(): array
    {
        return
        [
            // vendors
            // ----------------------

            new Asset(
                'vendor'.DIRECTORY_SEPARATOR.
                'itsjavi-fa'.DIRECTORY_SEPARATOR.
                'fontawesome-iconpicker.min.js'
            , Asset::JS),

            new Asset(
                DIRECTORY_SEPARATOR.
                'vendor'.DIRECTORY_SEPARATOR.
                'itsjavi-fa'.DIRECTORY_SEPARATOR.
                'fontawesome-iconpicker.min.css'
                , Asset::CSS),

            // plugin
            // ----------------------

            new Asset(
                DIRECTORY_SEPARATOR.
                'styles.css'
                , Asset::CSS),
        ];
    }

    public function registerRoutes(): array
    {
        return [
            RouteFactory::makeRoute('/generate/', generateController::class, 'generate')
                ->setTitle('Новый плагин')
                ->setIcon('fa-plus')
        ];
    }
}