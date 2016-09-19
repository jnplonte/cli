<?php
namespace Cilex\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

class TillReceipt extends Command
{
    private $iman_functions;

    private $helper;

    private $curencySelected;

    private $productSelected;
    private $productValue;
    private $productList;

    private $discountSelected;

    private $productSubTotal = 0;
    private $productTotal = 0;

    protected function configure()
    {
        $this
            ->setName('tillreceipt:run')
            ->setDescription('run till receipt');

        $this->iman_functions = new imanFunctions;  
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->helper = $this->getHelper('question');

        $this->curencySelected = $this->checkCurency($input, $output);
        
        $output->writeln('<info>PRODUCT LIST</info>');

        $this->productSelected = $this->checkProduct($input, $output);

        $this->discountSelected = $this->checkDiscount($input, $output);

        $output->writeln('<info>ITEM - PRICE</info>');
        foreach ($this->productSelected as $k => $v) {
            $key = array_search($v, $this->productList);
            $this->productSubTotal = floatval($this->productSubTotal) + floatval($this->productValue[$key]);

            $output->writeln($v);
        }

        $output->writeln('');
        $output->writeln('<info>SUBTOTAL - '.$this->productSubTotal.'</info>');
        $output->writeln('<info>DISCOUNT - '.$this->discountSelected.'</info>');

        $this->productTotal = floatval($this->productSubTotal) - floatval($this->discountSelected);
        
        $output->writeln('');
        $output->writeln('<info>TOTAL - '.$this->productTotal.'</info>');
    }

    public function checkCurency($input, $output){
        $currency = $this->iman_functions->_currencyList();

        $question = new ChoiceQuestion('Select Currency? ', $currency, 0);
        
        $question->setErrorMessage('Currency %s is invalid');

        return $this->helper->ask($input, $output, $question);
    }

    public function checkProduct($input, $output){
        $product = $this->iman_functions->_productList();

        foreach ($product as $key => $value) {
            $this->productValue[]  = $value->price->{$this->curencySelected};
            $this->productList[] = $value->name.' - '.$this->curencySelected.' '. $value->price->{$this->curencySelected};
        }

        $question = new ChoiceQuestion('Select Your Products (comma seperated) ', $this->productList, 0);
        $question->setMultiselect(true);

        return $this->helper->ask($input, $output, $question);
    }

    public function checkDiscount($input, $output){
        $question = new Question('Please Discount? ', 0);
        $question->setValidator(function ($answer) {
            if(!preg_match("/^-?[0-9]+(?:\.[0-9]{1,2})?$/", $answer)){
                throw new \RuntimeException(
                    'discount must be numeral characters'
                );
            }
            return $answer;
        });
        $question->setMaxAttempts(3);

        return $this->helper->ask($input, $output, $question);
    }

}
