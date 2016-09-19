<?php
namespace Cilex\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

class BankAccountClose extends Command
{
    private $iman_functions;

    protected function configure()
    {
        $this
            ->setName('bankaccount:close')
            ->setDescription('close a bank account (name)')
            ->addArgument('name', InputArgument::REQUIRED, 'name of the person you want to close an account');

        $this->iman_functions = new imanFunctions;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');

        if (ctype_alpha( str_replace(' ', '', $name) )) {
            $userlist = $this->iman_functions->_checkUser($name);
            if($userlist){
                if(count($userlist) >= 2){
                    foreach ($userlist as $key => $value) {
                        $valueQA[] = $value['id'];
                        $choiceQA[] = $value['id'].' - '.$value['name'];
                    }
                    $helper = $this->getHelper('question');
                    $question = new ChoiceQuestion('which user you want to close account? ', $choiceQA, 0);

                    $question->setErrorMessage('account id %s is invalid');

                    $deleteValue = $helper->ask($input, $output, $question);

                    if($deleteValue){
                        $key = array_search($deleteValue, $choiceQA);
                        $deleteId = $valueQA[$key];
                    }

                    if($this->iman_functions->_deleteUser($deleteId)){
                        $output->writeln('<info>'.$name.' sucessfully deleted to database</info>');
                    }else{
                        $output->writeln('<error>'.$name.' failed to deleted in database</error>');
                    }

                }else{
                    if($this->iman_functions->_deleteUser($userlist[0]['id'])){
                        $output->writeln('<info>'.$name.' sucessfully deleted to database</info>');
                    }else{
                        $output->writeln('<error>'.$name.' failed to deleted in database</error>');
                    } 
                }
            }else{
                $output->writeln('<error>'.$name.' is not database</error>');
            }
        }else{
            $output->writeln('<error>name must be alphabetic characters</error>');
        }
    }
}