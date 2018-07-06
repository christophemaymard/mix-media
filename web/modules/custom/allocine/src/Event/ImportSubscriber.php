<?php

namespace Drupal\allocine\Event;

use Drupal\allocine\ContentTypeManager;
use Drupal\allocine\Database;
use Drupal\allocine\TaxonomyManager;
use Drupal\allocine\WebService\Data\PictureMedia;
use Drupal\Core\File\FileSystem;
use Drupal\file\Entity\File;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Represents an event subscriber related to imported data from Allocine.
 */
class ImportSubscriber implements EventSubscriberInterface {
  /**
   * @var Database
   */
  private $database;
  
  /**
   * The taxonomy manager.
   * @var TaxonomyManager
   */
  private $taxonomyManager;
  
  /**
   * The content type manager.
   * @var ContentTypeManager
   */
  private $contentTypeManager;
  
  /**
   * The file system manager.
   * @var FileSystem
   */
  private $fileSystem;
  
  /**
   * {@inheritDoc}
   */
  public static function getSubscribedEvents() {
    $events = [];
    $events[CountryImportedEvent::NAME] = [
      ['onImportCountry', 0],
    ];
    $events[GenreImportedEvent::NAME] = [
      ['onImportGenre', 0],
    ];
    $events[MovieImportedEvent::NAME] = [
      ['onImportMovie', 0],
    ];
    $events[MediaCategoryImportedEvent::NAME] = [
      ['onImportMediaCategory', 0],
    ];
    $events[MediaImportedEvent::NAME] = [
      ['onImportPictureMedia', 0],
    ];
    
    return $events;
  }
  
  /**
   * Constructor.
   * 
   * @param   Database              $database
   * @param   TaxonomyManager       $taxonomyManager    The taxonomy manager.
   * @param   ContentTypeManager    $contentTypeManager The content type manager.
   * @param   FileSystem            $fileSystem         The file system manager.
   */
  public function __construct(
    Database $database, 
    TaxonomyManager $taxonomyManager,
    ContentTypeManager $contentTypeManager,
    FileSystem $fileSystem) {
    $this->database = $database;
    $this->taxonomyManager = $taxonomyManager;
    $this->contentTypeManager = $contentTypeManager;
    $this->fileSystem = $fileSystem;
  }
  
  /**
   * Actions when a CountryImportedEvent::NAME event is dispatched.
   * 
   * @param   CountryImportedEvent  $event  The event to process.
   */
  public function onImportCountry(CountryImportedEvent $event) {
    $country = $event->getCountry();
    
    // Determines whether a country is already mapped with a term.
    if (!$this->database->hasCountryByCode($country->code)) {
      // Creates a 'countries' term.
      $countryTerm = $this->taxonomyManager->createCountryTerm($country->name);
      
      // Creates the mapping between the Allocine country and the 'countries' term.
      $this->database->createCountry($country->code, $country->name, $countryTerm->id());
    }
  }
  
  /**
   * Actions when a GenreImportedEvent::NAME event is dispatched.
   * 
   * @param   GenreImportedEvent  $event  The event to process.
   */
  public function onImportGenre(GenreImportedEvent $event) {
    $genre = $event->getGenre();
    
    // Determines whether a genre is already mapped with a term.
    if (!$this->database->hasGenreByCode($genre->code)) {
      // Creates a 'genres' term.
      $genreTerm = $this->taxonomyManager->createGenreTerm($genre->name);
      
      // Creates the mapping between the Allocine genre and the 'genres' term.
      $this->database->createGenre($genre->code, $genre->name, $genreTerm->id());
    }
  }
  
  /**
   * Actions when a MovieImportedEvent::NAME event is dispatched.
   * 
   * @param   MovieImportedEvent  $event  The event to process.
   */
  public function onImportMovie(MovieImportedEvent $event) {
    $movie = $event->getMovie();
    
    // Determines whether a movie is already mapped with a content type.
    if (!$this->database->hasMovieByCode($movie->code)) {
      // Creates a 'movie' content type.
      $movieContentType = $this->contentTypeManager->createMovieContentType(
        $movie->title,
        $movie->synopsis,
        $movie->runtime,
        $movie->releaseDate
      );
      
      // Creates the mapping between the Allocine movie and the 'movie' content type.
      $this->database->createMovie($movie->code, $movie->title, $movieContentType->id());
    }
  }
  
  /**
   * Actions when a MediaCategoryImportedEvent::NAME event is dispatched.
   * 
   * @param   MediaCategoryImportedEvent  $event  The event to process.
   */
  public function onImportMediaCategory(MediaCategoryImportedEvent $event) {
    $category = $event->getMediaCategory();
    
    // Determines whether a media category is already mapped with a term.
    if (!$this->taxonomyManager->hasMediaCategoryTermById($category->code)) {
      // Creates a 'media_categories' term.
      // The Allocine code of the media category is used as TID.
      $this->taxonomyManager->createMediaCategoryTerm(
        $category->code,
        $category->name
      );
    }
  }
  
  /**
   * Actions when a MediaImportedEvent::NAME event is dispatched.
   * 
   * @param   MediaImportedEvent    $event  The event to process.
   */
  public function onImportPictureMedia(MediaImportedEvent $event) {
    $media = $event->getMedia();
    
    // Only process picture media.
    if (!$media instanceof PictureMedia) {
      return;
    }
    
    // Determines whether a media is already mapped with a content type.
    if (!$this->database->hasMediaByCode($media->code)) {
      // Downloads the picture file and creates an image entity.
      $image = $this->createFileEntityFromUrl($media->url);
      
      // The Allocine code of the media category is used as TID.
      $category = $this->taxonomyManager->getMediaCategoryById($media->category->code);
      
      // Creates a 'picturemedia' content type.
      $contentType = $this->contentTypeManager->createPictureMediaContentType(
        $media->title,
        $image->id(),
        $category,
        $media->copyright
      );
      
      // Creates the mapping between the Allocine media and the 'picturemedia' content type.
      $this->database->createMedia($media->code, $media->type, $contentType->id());
    }
  }
  
  /**
   * 
   * @param   string  $url
   * @return  File
   * 
   * @throws  \Exception  When the file cannot be downloaded.
   * @throws  \Exception  When the file cannot be created.
   */
  private function createFileEntityFromUrl($url) {
    if (FALSE === $data = file_get_contents($url)) {
      throw new \Exception(sprintf('The file, located at "%s", cannot be downloaded.', $url));
    }
    
    $file = File::create();
    $fileName = $this->fileSystem->basename($url);
    $file->setFileName($fileName);
    $file->setFileUri(sprintf("public://%s", $fileName));
    $file->setMimeType('image/'.pathinfo($url, PATHINFO_EXTENSION));
    
    // Create the directory if necessary.
    $dir = $this->fileSystem->dirname($url);
    
    if (!file_exists($dir)) {
      $this->fileSystem->mkdir($dir, 0770, TRUE);
    }
    
    // Stores the content of the file.
    if (file_put_contents($file->getFileUri(), $data) === FALSE) {
      throw new \Exception(sprintf('The file "%s" cannot be created.', $file->getFileUri()));
    }
    
    $file->save();
    
    return $file;
  }
}
