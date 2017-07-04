<?php
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Tester\CommandTester;

class TestBankAccount extends \PHPUnit_Framework_TestCase{

    private $name = 'john paul onte test';
    private $deposit = '500';
    private $withdraw = '100';
    private $withdrawOverDraft = '600';

    private $application;

    function __construct(){
        $this->application = new Application();
    }

    private function getInputStream($input){
        $stream = fopen('php://memory', 'r+', false);
        fputs($stream, $input);
        rewind($stream);

        return $stream;
    }

    private function executeTest($cmd=null, $data=array(), $hasChoice=false){
      if(!empty($cmd)){
        $command = $this->application->find($cmd);
        $commandTester = new CommandTester($command);
        if($hasChoice){
          $helper = $command->getHelper('question');
          $helper->setInputStream($this->getInputStream("0"));
        }
        $execData = array( 'command' => $command->getName());
        $commandTester->execute(array_merge($execData,$data));

        return $commandTester;
      }else{
        return null;
      }
    }

    public function testOpen(){
        $this->application->add(new \Cilex\Command\BankAccountOpen());

        $commandTester = $this->executeTest('bankaccount:open', array('name' => $this->name), false);

        $this->assertRegExp('/sucessfully addeed/', $commandTester->getDisplay());
    }

    public function testDeposit(){
        $this->application->add(new \Cilex\Command\BankAccountDeposit());

        $commandTester = $this->executeTest('bankaccount:deposit', array(
          'name' => $this->name,
          'amount' => $this->deposit), true);

        $this->assertEquals($this->deposit, $commandTester->getInput()->getArgument('amount'));
        $this->assertRegExp('/deposit success/', $commandTester->getDisplay());
    }

    public function testDisplayBalance(){
        $this->application->add(new \Cilex\Command\BankAccountDisplayBalance());

        $commandTester = $this->executeTest('bankaccount:displaybalance', array('name' => $this->name), true);

        $this->assertRegExp('/current balance/', $commandTester->getDisplay());
    }

    public function testWidthraw(){
        $this->application->add(new \Cilex\Command\BankAccountWithdraw());

        $commandTester = $this->executeTest('bankaccount:withdraw', array(
          'name' => $this->name,
          'amount' => $this->withdraw), true);

        $this->assertEquals($this->withdraw, $commandTester->getInput()->getArgument('amount'));
        $this->assertRegExp('/withdraw success/', $commandTester->getDisplay());
    }

    public function testOverdraft(){
        $this->application->add(new \Cilex\Command\BankAccountOverdraft());

        $commandTester = $this->executeTest('bankaccount:overdraft', array('name' => $this->name), true);

        $this->assertRegExp('/overdraft success/', $commandTester->getDisplay());
    }

    public function testWidthrawOverDraft(){
        $this->application->add(new \Cilex\Command\BankAccountWithdraw());

        $commandTester = $this->executeTest('bankaccount:withdraw', array(
          'name' => $this->name,
          'amount' => $this->withdrawOverDraft), true);

        $this->assertEquals($this->withdrawOverDraft, $commandTester->getInput()->getArgument('amount'));
        $this->assertRegExp('/withdraw success/', $commandTester->getDisplay());
    }

    public function testClose(){
        $this->application->add(new \Cilex\Command\BankAccountClose());

        $commandTester = $this->executeTest('bankaccount:close', array('name' => $this->name), true);

        $this->assertRegExp('/sucessfully deleted/', $commandTester->getDisplay());
    }
}
