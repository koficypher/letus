<?php namespace Citrus;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use GuzzleHttp\ClientInterface;

class NewCommand extends Command {

    private $client;

    public function __construct(ClientInterface $client)
    {
      $this->client = $client;
      parent::__construct();
    }

    public function configure()
    {
        $this->setName('new')
              ->setDescription('Creates new laravel application') 
              ->addArgument('name', InputArgument::REQUIRED, 'application name');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
      //check if the folder already exists 
      $directory = getcwd().'/'. $input->getArgument('name');
       $this->checkApplicationExists($directory,$output);

      //download laravel nightly
       $this->download($this->makeFileName())
             ->extract();

      //extract the zip


      //alert the user
    }




    private function checkApplicationExists($directory,OutputInterface $output)
    {
      if(is_dir($directory)){

        $output->writeln('<error>Application already exists</error>');

        exit(1);
      }
    }

    private function makeFileName()
    {
        return getcwd() . '/laravel_' . md5(time().uniqid());
    }

  private function download($zipfile)
  {
    $response = $this->client->get('http://cabinet.laravel.com/latest.zip')->getBody();

    file_put_contents($zipfile, $response);

    return $this;
  }
}