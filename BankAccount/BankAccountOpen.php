<?php
namespace Cilex\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Iman\Command as imanCommand;

class BankAccountOpen extends Command
{
    private $iman_functions;

    private $iman_helpers;

    protected function configure()
    {
        $this
            ->setName('bankaccount:open')
            ->setDescription('open a bank account (name)')
            ->addArgument('name', InputArgument::REQUIRED, 'name of the person you want to open an account');

        $this->iman_functions = new imanCommand\imanFunctions();

        $this->iman_helpers = new imanCommand\imanHelpers();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');

        if (ctype_alpha( str_replace(' ', '', $name) )) {
            if($this->iman_functions->_insertUser($name)){
                $output->writeln($this->iman_helpers->_throwMessage('info', $name.' sucessfully addeed to database'));
            }else{
                $output->writeln($this->iman_helpers->_throwMessage('error', $name.' failed to add in database'));
            }
        }else{
            $output->writeln($this->iman_helpers->_throwMessage('error', null, 'alpha-error'));
        }
    }

}
