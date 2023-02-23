[![Build Status](https://travis-ci.org/pcuser42/cakephp-webpack-asset-loader.svg?branch=main)](https://travis-ci.org/pcuser42/cakephp-webpack-asset-loader)
[![codecov](https://codecov.io/gh/pcuser42/cakephp-webpack-asset-loader/branch/master/graph/badge.svg)](https://codecov.io/gh/pcuser42/cakephp-webpack-asset-loader)

# WebpackAssetLoader plugin for CakePHP

## Installation

You can install this plugin into your CakePHP application using [composer](https://getcomposer.org).

The recommended way to install composer packages is:

```
composer require pcuser42/cakephp-webpack-asset-loader
```

## Use

Load the plugin in `Application`

```php
$this->addPlugin('Pcuser42/WebpackAssetLoader');
```


Load the helper in `AppView`

```php
$this->loadHelper('Pcuser42/WebpackAssetLoader.Asset');
```

Load all assets for an entrypoint in your template file:

```php
<?=$this->Asset->getEntries('js')?>
```

Or load assets and output them one type at a time in your view file:

```php
$this->Asset->loadEntryDeferred('app');
...
<?=$this->Asset->getDeferredEntries('js')?>
...
<?=$this->Asset->getDeferredEntries('css')?>
