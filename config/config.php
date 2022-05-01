<?php
// Configuració de l'aplicació
// Accés a la base de dades

$dbParams = [
	'driver' =>	'pdo_mysql',
	'host' =>	'127.0.0.1',
	'dbname' =>	'entrades',
	'user' =>	'usr_generic',
	'password' =>'Thico@2020'
	];

// Estamos en modo desarrollo?
$dev = true;