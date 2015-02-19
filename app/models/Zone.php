<?php 
class Zone extends Eloquent {
    protected $table = 'Zone';
    private $zones_width = 1138;  

    public function object() {
    	return $this->belongsTo('Object'); 
    }

    public function zoneobjects() {
    	return $this->hasMany('ZoneObject');
    }

    public function getStyleAttribute() {
    	return "top: ".$this->top."px; left: ".$this->left."%; width: ".$this->width."%; height: ".$this->height."px;";
    }
}