<?php
namespace Cilex\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

use Iman\Command as imanCommand;

class BankAccountClose extends Command
{
    private $iman_functions;

    private $iman_helpers;

    protected function configure()
    {
        $this
            ->setName('bankaccount:close')
            ->setDescription('close a bank account (name)')
            ->addArgument('name', InputArgument::REQUIRED, 'name of the person you want to close an account');

        $this->iman_functions = new imanCommand\imanFunctions();

        $this->iman_helpers = new imanCommand\imanHelpers();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');

        if (ctype_alpha( str_replace(' ', '', $name) )) {
            $userlist = $this->iman_functions->_checkUser($name);
            if($userlist){
                $userClose = false;
                if(count($userlist) >= 2){
                    foreach ($userlist as $key => $value) {
                        $valueQA[] = $value['id'];
                        $choiceQA[] = 'ID: '.$value['id'].' NAME: '.$value['name'];
                    }
                    $helper = $this->getHelper('question');
                    $question = new ChoiceQuestion('which user you want to close account? ', $choiceQA, 0);

                    $question->setErrorMessage('account id %s is invalid');

                    $deleteValue = $helper->ask($input, $output, $question);

                    if($deleteValue){
                        $key = array_search($deleteValue, $choiceQA);
                        $deleteId = $valueQA[$key];
                    }
                    $userClose = $this->iman_functions->_deleteUser($deleteId);
                }else{
                    $userClose = $this->iman_functions->_deleteUser($userlist[0]['id']);
                }
                if($userClose){
                    $output->writeln($this->iman_helpers->_throwMessage('info', $name.' sucessfully deleted in our database'));
                }else{
                    $output->writeln($this->iman_helpers->_throwMessage('error', $name.' failed to deleted in our database'));
                }
            }else{
                $output->writeln($this->iman_helpers->_throwMessage('error', null, 'invalid-user'));
            }
        }else{
            $output->writeln($this->iman_helpers->_throwMessage('error', null, 'alpha-error'));
        }
    }
}
