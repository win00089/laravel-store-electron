<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\App;
use LogicException;

trait Translatable{
  protected $defaultLocale = 'ru';

  public function __($originfieldName){
    $locale = App::getLocale() ?? $this->defaultLocale;

    if($locale === 'en'){
      $fieldName = $originfieldName . '_en';
    }else{
      $fieldName = $originfieldName;
    }

    $attributes = array_keys($this->attributes);
 
    if (!in_array($fieldName, $attributes)) {
        throw new \LogicException('no such attribute for model ' . get_class($this));
    }

    if($locale === 'en' && (is_null($this->$fieldName) || empty($this->$fieldName))){
      return $this->$originfieldName;
    }

    return $this->$fieldName;
  }
}