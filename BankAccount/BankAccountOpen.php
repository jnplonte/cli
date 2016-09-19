<?php
namespace Cilex\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class BankAccountOpen extends Command
{
    private $iman_functions;

    protected function configure()
    {
        $this
            ->setName('bankaccount:open')
            ->setDescription('open a bank account (name)')
            ->addArgument('name', InputArgument::REQUIRED, 'name of the person you want to open an account');

        $this->iman_functions = new imanFunctions;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');

        if (ctype_alpha( str_replace(' ', '', $name) )) {
            if($this->iman_functions->_insertUser($name)){
                $output->writeln('<info>'.$name.' sucessfully addeed to database</info>');
            }else{
                $output->writeln('<error>'.$name.' failed to add in database</error>');
            }
        }else{
            $output->writeln('<error>name must be alphabetic characters</error>');
        }
    }

}
