<?php

namespace Drupal\allocine\Command;

use Drupal\allocine\Importing\MovieImporter;
use Drupal\Console\Annotations\DrupalCommand;
use Drupal\Console\Core\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Represents the command to import a movie from Allocine.
 *
 * @DrupalCommand (
 *     extension="allocine",
 *     extensionType="module"
 * )
 */
class MovieImportCommand extends ContainerAwareCommand {
  /**
   * Prefix used in console message translations.
   */
  const MSG_PREFIX = 'commands.allocine.movie.import';
  
  /**
   * @var MovieImporter
   */
  private $movieImporter;
  
  /**
   * Constructor.
   * 
   * @param   MovieImporter $movieImporter
   */
  public function __construct(MovieImporter $movieImporter) {
    $this->movieImporter = $movieImporter;
    
    parent::__construct();
  }
  
  /**
   * {@inheritDoc}
   */
  protected function configure() {
    // Note: translate() cannot be used at this stage because the translator 
    // is not initialized yet.
    $this
      ->setName('allocine:movie:import')
      ->setDescription($this->trans(self::MSG_PREFIX.'.description'))
      ->addArgument(
          'code', 
          InputArgument::REQUIRED,
          $this->trans(self::MSG_PREFIX.'.arguments.code'), 
          NULL
      )
    ;
  }
  
  /**
   * {@inheritDoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    $startTime = microtime(TRUE);
    
    $io = $this->getIo();
    
    // Retrieves the 'code' argument.
    $code = $input->getArgument('code');
    
    $io->info($this->translate(self::MSG_PREFIX.'.messages.execute', ['@code' => $code]));
    
    try {
      // Imports movie data from Allocine.
      $movie = $this->movieImporter->importMovie($code);
      
      $duration = microtime(TRUE) - $startTime;
      
      $io->info($this->translate(self::MSG_PREFIX.'.messages.success', [
        '@title' => $movie->title,
        '@code' => $code,
        '@duration' => $duration,
      ]));
    } catch (\Exception $ex) {
      // Displays error message on exception.
      $io->error($this->translate(self::MSG_PREFIX.'.messages.error', ['@message' => $ex->getMessage()]));
    }
  }
  
  /**
   * Translates the given message.
   * 
   * THIS METHOD HAS BEEN CREATED BECAUSE THE trans() METHOD DOES NOT MANAGE 
   * PARAMETERS!
   * 
   * @param   string  $id         The message id.
   * @param   array   $parameters An array of parameters for the message (default to an empty array).
   * 
   * @see     Symfony\Component\Translation\Translator
   */
  private function translate($id, array $parameters = []) {
    return $this->translator->getTranslator()->trans($id, $parameters);
  }
}
