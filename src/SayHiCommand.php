<?php namespace Citrus;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class SayHiCommand extends Command {

    public function configure()
    {
        $this->setName('sayHi')
              ->setDescription('greeting the invoking user of the command') 
              ->addArgument('name', InputArgument::OPTIONAL, 'your name')
              ->addOption('greeting',null, InputOption::VALUE_OPTIONAL, 'overides the default greeting','Hello');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
      // $message ='Hello '. $input->getArgument('name');

         $message = sprintf('%s, %s', $input->getOption('greeting'), $input->getArgument('name'));

         $output->writeln("<info>{$message}<info>");
    }
}