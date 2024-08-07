<?php

namespace Pcuser42\WebpackAssetLoader\View\Helper;

use Cake\Core\Configure;
use Cake\Routing\Router;
use Cake\View\Helper;
use Cake\View\Helper\HtmlHelper;

/**
 * Asset helper
 *
 * @property HtmlHelper $Html
 */
class AssetHelper extends Helper {
	/**
	 * Default configuration.
	 *
	 * @var array
	 */
	protected array $_defaultConfig = [
		'entrypointFile' => WWW_ROOT . 'build' . DS . 'entrypoints.json',
		'defaultOptions' => [
			'js' => [
				'block' => 'script',
			],
			'css' => [
				'block' => 'css',
			],
		],
		'configurationKey' => 'pcuser42.WebpackAssetLoader.entries',
	];

	public array $helpers = ['Html'];

	private array $entrypoints = [];

	public function initialize(array $config): void {
		parent::initialize($config);

		if (!Configure::read($this->getConfig('configurationKey'))) {
			Configure::write($this->getConfig('configurationKey'), [
				'js'  => [],
				'css' => [],
			]);
		}

		try {
			$json = file_get_contents($this->getConfig('entrypointFile'));

			if (!$json) {
				throw new \Exception('Could not load entrypoints file.');
			}
		} catch (\Exception) {
			throw new \Exception('Could not load entrypoints file.');
		}

		$this->entrypoints = json_decode($json, true, flags: JSON_THROW_ON_ERROR);

		if (!$this->entrypoints) {
			throw new \Exception('Could not parse entrypoints file.');
		}
	}

	public function loadEntry(string $name, array $options = []): string {
		if (!isset($this->entrypoints['entrypoints'][$name])) {
			throw new \Exception("Unknown Entry '" . $name . "'");
		}

		$assets = $this->entrypoints['entrypoints'][$name];

		return $this->_writeEntries($assets, 'js', $options + ['js' => []]) .
			$this->_writeEntries($assets, 'css', $options + ['css' => []]);
	}

	public function loadEntryDeferred(string $name, array $options = []): void {
		if (!isset($this->entrypoints['entrypoints'][$name])) {
			throw new \Exception("Unknown Entry '" . $name . "'");
		}

		$assets = $this->entrypoints['entrypoints'][$name];

		$assets['js']  ??= [];
		$assets['css'] ??= [];

		$deferredAssets = Configure::read($this->getConfig('configurationKey'));
		foreach ($assets['js'] as $asset) {
			// use asset as key to avoid duplicates
			$deferredAssets['js'][$asset] = $asset;
		}

		foreach ($assets['css'] as $asset) {
			$deferredAssets['css'][$asset] = $asset;
		}

		Configure::write($this->getConfig('configurationKey'), $deferredAssets);
	}

	public function getDeferredEntries(string $type, array $options = []): string {
		if ('js' !== $type && 'css' !== $type) {
			throw new \Exception(sprintf("Unknown asset type '%s'.", $type));
		}

		$deferredAssets = Configure::read($this->getConfig('configurationKey'));

		return $this->_writeEntries($deferredAssets, $type, [
			$type => $options,
		]);
	}

	private function _writeEntries(array $assets, string $type, array $options): string {
		//get the base URL for the root page, so that if Webpack's manifest has this in their URLs we can avoid duplicating the subfolder
		$baseUrl = Router::url('/');

		$assets[$type] ??= [];

		$publicPath = $this->entrypoints['publicPath'] ?? "";

		$func = 'js' === $type ? 'script' : 'css';

		$output = "";
		foreach ($assets[$type] as $asset) {
			if (str_starts_with((string) $asset, $baseUrl)) {
				$asset = '/' . substr((string) $asset, strlen($baseUrl));
			}

			$output .= $this->Html->$func(
				$publicPath . $asset,
				(
					$options[$type] ?? $this->getConfig('defaultOptions.js') ?: []
				) + ['integrity' => $this->entrypoints[$asset]['integrity'] ?? null]
			) . "\n";
		}

		return $output;
	}
}
