<?php
if (!$loader = include __DIR__.'/vendor/autoload.php') {
    die('You must set up the project dependencies.');
}
include __DIR__.'/TillReceipt/TillReceipt.php';

include __DIR__.'/imanFunctions.php';

foreach (glob("BankAccount/*.php") as $filename)
{
    include __DIR__.'/'.$filename;
}

$app = new \Cilex\Application('Cilex');

$app->command(new \Cilex\Command\BankAccountOpen());
$app->command(new \Cilex\Command\BankAccountClose());
$app->command(new \Cilex\Command\BankAccountDisplayBalance());
$app->command(new \Cilex\Command\BankAccountDeposit());
$app->command(new \Cilex\Command\BankAccountWithdraw());
$app->command(new \Cilex\Command\BankAccountOverdraft());

$app->command(new \Cilex\Command\TillReceipt());

$app->run();