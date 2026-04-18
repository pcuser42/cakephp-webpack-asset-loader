<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\ClassMethod\LocallyCalledStaticMethodToNonStaticRector;
use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
	$rectorConfig->paths([
		__DIR__ . '/src',
	]);

	// register a single rule
	$rectorConfig->rule(LocallyCalledStaticMethodToNonStaticRector::class);

	// define sets of rules
	$rectorConfig->sets([
		LevelSetList::UP_TO_PHP_83,
		SetList::CODE_QUALITY,
		SetList::CODING_STYLE,
		SetList::DEAD_CODE,
		SetList::EARLY_RETURN,
		SetList::TYPE_DECLARATION,
	]);
};
