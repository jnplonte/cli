# IMAN TEST


## Dependencies
* php: [http://php.net/](http://php.net/)
* mysql: [https://www.mysql.com/](https://www.mysql.com/)
* cilex: [http://cilex.github.io/](http://cilex.github.io/)
* phpunit: [https://phpunit.de/](https://phpunit.de/)
* composer: [https://getcomposer.org/](https://getcomposer.org/)


## Installation
- Install dependencies by running `composer install`
- Create database and change database config on `{root}\config.json`
- Import database by running `mysql -u{username} -p {database-name} < iman_bankaccount.sql`


## How to Use
#### view available command
- run `php iman.php` or `./iman.php` it will show the available command you can use

#### open account
- `php iman.php bankaccount:open "<name>"`
- sample: `php iman.php bankaccount:open "john paul"`

#### deposit funds
- `php iman.php bankaccount:deposit "<name>" "<amount>"`
- sample: `php iman.php bankaccount:deposit "john paul" "500"`

#### display balance
- `php iman.php bankaccount:displaybalance "<name>"`
- sample: `php iman.php bankaccount:displaybalance "john paul"`

#### withdraw funds
- `php iman.php bankaccount:withdraw "<name>" "<amount>"`
- sample: `php iman.php bankaccount:withdraw "john paul" "100"`

#### apply agreed overdraft
- `php iman.php bankaccount:overdraft "<name>"`
- sample: `php iman.php bankaccount:overdraft "john paul"`

#### close account
- `php iman.php bankaccount:close "<name>"`
- sample: `php iman.php bankaccount:close "john paul"`


## Testing
- run `phpunit --bootstrap TestBankAccount/imanTest.php TestBankAccount/TestBankAccount.php`
