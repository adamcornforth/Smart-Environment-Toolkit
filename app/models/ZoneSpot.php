<?php 
class ZoneSpot extends Eloquent {
    protected $table = 'ZoneSpot';

    public function spot() {
    	return $this->belongsTo('Spot');
    }

    public function zone() {
    	return $this->belongsTo('Zone');
    }

    public function job() {
    	return $this->belongsTo('Job');
    }

}