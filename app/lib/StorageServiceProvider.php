<?php namespace Sunspot\Storage; 
 
use Illuminate\Support\ServiceProvider;
 
class StorageServiceProvider extends ServiceProvider {
 
  public function register()
  {
    $this->app->bind(
      'Sunspot\Storage\ZoneRepository',
      'Sunspot\Storage\EloquentZoneRepository'
    );
  }
 
}
