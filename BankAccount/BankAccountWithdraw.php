<?php
namespace Cilex\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

use Iman\Command as imanCommand;

class BankAccountWithdraw extends Command
{
    private $iman_functions;

    private $iman_helpers;

    protected function configure()
    {
        $this
            ->setName('bankaccount:withdraw')
            ->setDescription('withdraw money (name) (amount)')
            ->addArgument('name', InputArgument::REQUIRED, 'name of the person you want to withdraw money')
            ->addArgument('amount', InputArgument::REQUIRED, 'amount of withdraw money');

        $this->iman_functions = new imanCommand\imanFunctions();

        $this->iman_helpers = new imanCommand\imanHelpers();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');

        $amount = $input->getArgument('amount');

        if (ctype_alpha( str_replace(' ', '', $name) )) {
            if(preg_match("/^-?[0-9]+(?:\.[0-9]{1,2})?$/", $amount)){
                $userlist = $this->iman_functions->_checkUser($name);
                if($userlist){
                    $userBalance = "0.00";
                    $userRemoveBalance = false;
                    if(count($userlist) >= 2){
                        foreach ($userlist as $key => $value) {
                            $valueQA[] = $value['id'];
                            $choiceQA[] = 'ID: '.$value['id'].' NAME: '.$value['name'];
                        }
                        $helper = $this->getHelper('question');
                        $question = new ChoiceQuestion('which user you want to withdraw the '.number_format($amount,2).'? ', $choiceQA, 0);

                        $question->setErrorMessage('account id %s is invalid');

                        $deleteValue = $helper->ask($input, $output, $question);
                        $Id = 0; //set default value

                        if($deleteValue){
                            $key = array_search($deleteValue, $choiceQA);
                            $Id = $valueQA[$key];
                        }

                        $userRemoveBalance = $this->iman_functions->_removeBalance($Id, $amount);
                        $userBalance = $this->iman_functions->_displayBalance($Id);
                    }else{
                      $userRemoveBalance = $this->iman_functions->_removeBalance($userlist[0]['id'], $amount);
                      $userBalance = $this->iman_functions->_displayBalance($userlist[0]['id']);
                    }
                    if($userRemoveBalance){
                        $output->writeln($this->iman_helpers->_throwMessage('info', 'withdraw success, remaining balance as of '.date('l jS \of F Y h:i:s A').' is: '.$userBalance));
                    }else{
                        $output->writeln($this->iman_helpers->_throwMessage('error', null, 'denied-user'));
                    }
                }else{
                    $output->writeln($this->iman_helpers->_throwMessage('error', null, 'invalid-user'));
                }
            }else{
                $output->writeln($this->iman_helpers->_throwMessage('error', null, 'num-error'));
            }
        }else{
            $output->writeln($this->iman_helpers->_throwMessage('error', null, 'alpha-error'));
        }
    }
}
