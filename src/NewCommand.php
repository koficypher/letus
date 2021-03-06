<?php namespace Citrus;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use GuzzleHttp\ClientInterface;
use ZipArchive;

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

      $output->writeln('<info>Crafting application ...</info>');

       $this->checkApplicationExists($directory,$output);

      //download laravel nightly
       $this->download($zipFile = $this->makeFileName())
      //extract the zip
             ->extract($zipFile, $directory)
     //clean up the directory
             ->cleanUp($zipFile);

      //alert the user
      $output->writeln('<comment>Application ready!!</comment>');
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

  private function extract($zipFile, $directory) 
  {
     $archive = new ZipArchive;

     $archive->open($zipFile);

     $archive->extractTo($directory);

     $archive->close();
     
     return $this;
  }

  private function cleanUp($zipFile)
  {
      @chmod($zipFile, 0777);

      @unlink($zipFile);

      return $this;
  }
}