#!/usr/bin/env php

<?php
$upOne = realpath(__DIR__ . '/..');

if (!$loader = include $upOne.'/vendor/autoload.php') {
    die('You must set up the project dependencies.');
}

foreach (glob('BankAccount/*.php') as $filename){
    include $upOne.'/'.$filename;
}

include $upOne.'/imanFunctions.php';
include $upOne.'/imanHelpers.php';
