<?php

namespace Pcuser42\WebpackAssetLoader;

use Cake\Core\BasePlugin;

/**
 * Plugin for WebpackAssetLoader
 */
class Plugin extends BasePlugin {
	/**
     * Plugin name.
     *
     * @var string
     */
    protected $name = 'WebpackAssetLoader';

    /**
     * Do bootstrapping or not
     *
     * @var bool
     */
    protected $bootstrapEnabled = false;

    /**
     * Load routes or not
     *
     * @var bool
     */
    protected $routesEnabled = false;
}
