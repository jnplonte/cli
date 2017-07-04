<?php
namespace Cilex\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

use Iman\Command as imanCommand;

class BankAccountOverdraft extends Command
{
    private $iman_functions;

    private $iman_helpers;

    protected function configure()
    {
        $this
            ->setName('bankaccount:overdraft')
            ->setDescription('overdraft account (name)')
            ->addArgument('name', InputArgument::REQUIRED, 'name of the person you want to overdraft account');

        $this->iman_functions = new imanCommand\imanFunctions();

        $this->iman_helpers = new imanCommand\imanHelpers();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');

        if (ctype_alpha( str_replace(' ', '', $name) )) {
            $userlist = $this->iman_functions->_checkUser($name);
            if($userlist){
                $userOverdraft = false;
                if(count($userlist) >= 2){
                    foreach ($userlist as $key => $value) {
                        $valueQA[] = $value['id'];
                        $choiceQA[] = 'ID: '.$value['id'].' NAME: '.$value['name'];
                    }
                    $helper = $this->getHelper('question');
                    $question = new ChoiceQuestion('which user you want to overdraft? ', $choiceQA, 0);

                    $question->setErrorMessage('account id %s is invalid');

                    $deleteValue = $helper->ask($input, $output, $question);
                    $Id = 0; //set default value

                    if($deleteValue){
                        $key = array_search($deleteValue, $choiceQA);
                        $Id = $valueQA[$key];
                    }
                    $userOverdraft = $this->iman_functions->_overdraftAccount($Id);
                }else{
                    $userOverdraft = $this->iman_functions->_overdraftAccount($userlist[0]['id']);
                }
                if($userOverdraft){
                    $output->writeln($this->iman_helpers->_throwMessage('info', $name.' overdraft success'));
                }else{
                    $output->writeln($this->iman_helpers->_throwMessage('error', $name.' overdraft failed'));
                }
            }else{
                $output->writeln($this->iman_helpers->_throwMessage('error', null, 'invalid-user'));
            }
        }else{
            $output->writeln($this->iman_helpers->_throwMessage('error', null, 'alpha-error'));
        }
    }
}
