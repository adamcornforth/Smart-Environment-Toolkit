<?php 
class Zone extends Eloquent {
    protected $table = 'Zone'; 

    public function object() {
    	return $this->belongsTo('Object'); 
    }
}