<?php

namespace Sirius;

spl_autoload_register(function(
	$class
) {
	if (strpos($class, __NAMESPACE__ . '\\') !== 0) {
		return false;
	}
	return include (__DIR__ . substr($class, strlen(__NAMESPACE__)) .  '.php');
} );

