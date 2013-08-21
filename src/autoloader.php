<?php

namespace Sirius;

spl_autoload_register(function(
	$class
) {
	if (strpos($class, __NAMESPACE__ . '\\') !== 0) {
		return false;
	}
	$file = __DIR__ . substr($class, strlen(__NAMESPACE__)) .  '.php';
	if (file_exists($file)) {
		return include_once($file);
	}
	return false;
});

