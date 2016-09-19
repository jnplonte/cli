<?php

/*
 * This file is part of the Cilex framework.
 *
 * (c) Mike van Riel <mike.vanriel@naenius.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cilex\Tests\Command;

use \Cilex\Command;

class MockBankAccount extends Command\Command {}

/**
 * Command\Command test cases.
 *
 * @author Mike van Riel <mike.vanriel@naenius.com>
 */
class TestBankAccount extends \PHPUnit_Framework_TestCase
{
    /** @var \Cilex\Command\Command */
    protected $fixture = null;


    public function testOpen()
    {
        $app = new \Cilex\Application('Test');
        $this->fixture = new MockBankAccount('bankaccount:open');
        $app->command($this->fixture);

        $this->assertSame($app, $this->fixture->getContainer());
    }

    public function testClose()
    {
        $app = new \Cilex\Application('Test');
        $this->fixture = new MockBankAccount('bankaccount:open');
        $app->command($this->fixture);

        $this->assertSame($app, $this->fixture->getContainer());
    }

    public function testDeposit()
    {
        $app = new \Cilex\Application('Test');
        $this->fixture = new MockBankAccount('bankaccount:deposit');
        $app->command($this->fixture);

        $this->assertSame($app, $this->fixture->getContainer());
    }

    public function testDisplayBalance()
    {
        $app = new \Cilex\Application('Test');
        $this->fixture = new MockBankAccount('bankaccount:displaybalance');
        $app->command($this->fixture);

        $this->assertSame($app, $this->fixture->getContainer());
    }

    public function testOverdraft()
    {
        $app = new \Cilex\Application('Test');
        $this->fixture = new MockBankAccount('bankaccount:overdraft');
        $app->command($this->fixture);

        $this->assertSame($app, $this->fixture->getContainer());
    }

    public function testWidthraw()
    {
        $app = new \Cilex\Application('Test');
        $this->fixture = new MockBankAccount('bankaccount:withdraw');
        $app->command($this->fixture);

        $this->assertSame($app, $this->fixture->getContainer());
    }

}
