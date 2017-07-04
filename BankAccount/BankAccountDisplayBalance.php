<?php
namespace Cilex\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

use Iman\Command as imanCommand;

class BankAccountDisplayBalance extends Command
{
    private $iman_functions;

    private $iman_helpers;

    protected function configure()
    {
        $this
            ->setName('bankaccount:displaybalance')
            ->setDescription('display account balance (name)')
            ->addArgument('name', InputArgument::REQUIRED, 'name of the person you want to display account balance');

        $this->iman_functions = new imanCommand\imanFunctions();

        $this->iman_helpers = new imanCommand\imanHelpers();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');

        if (ctype_alpha( str_replace(' ', '', $name) )) {
            $userlist = $this->iman_functions->_checkUser($name);
            if($userlist){
                $userBalance = "0.00";
                if(count($userlist) >= 2){
                    foreach ($userlist as $key => $value) {
                        $valueQA[] = $value['id'];
                        $choiceQA[] = 'ID: '.$value['id'].' NAME: '.$value['name'];
                    }
                    $helper = $this->getHelper('question');
                    $question = new ChoiceQuestion('which user you want to display balance? ', $choiceQA, 0);

                    $question->setErrorMessage('account id %s is invalid');

                    $deleteValue = $helper->ask($input, $output, $question);
                    $Id = 0; //set default value

                    if($deleteValue){
                        $key = array_search($deleteValue, $choiceQA);
                        $Id = $valueQA[$key];
                    }

                    $userBalance = $this->iman_functions->_displayBalance($Id);
                }else{
                    $userBalance = $this->iman_functions->_displayBalance($userlist[0]['id']);
                }
                $output->writeln($this->iman_helpers->_throwMessage('info', 'current balance as of '.date('l jS \of F Y h:i:s A').' is: '.$userBalance));
            }else{
                $output->writeln($this->iman_helpers->_throwMessage('error', null, 'invalid-user'));
            }
        }else{
            $output->writeln($this->iman_helpers->_throwMessage('error', null, 'alpha-error'));
        }
    }
}
