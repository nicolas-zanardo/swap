<?php

//local
date_default_timezone_set('Europe/Paris');
setlocale(LC_ALL, 'fr_FR.utf8', 'fra.utf8');

// SESSION
session_name('swap');
session_start();

// Format money
$fmt = new NumberFormatter( 'fr_FR', NumberFormatter::CURRENCY );

// Inclusion function
require_once __DIR__ . "/function.php";