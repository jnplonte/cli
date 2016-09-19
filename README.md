## HOW TO USE

NOTE: database config is on config.json, DB dump is on /iman_bankaccount.sql

install dependencies -> composer install
run app -> php iman.php


## CODE EXAMPLE

php iman.php bankaccount:open 'john paul onte'


## TEST CODE EXAMPLE

phpunit --bootstrap vendor/autoload.php BankAccount/TestBankAccount.php
