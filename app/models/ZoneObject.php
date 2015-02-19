<?php 
class ZoneObject extends Eloquent {
    protected $table = 'ZoneObject';

    public function object() {
    	return $this->belongsTo("Object");
    }

    public function getStyleAttribute() {
    	return "top: ".$this->top."px; left: ".$this->left."%; width: ".$this->width."%; height: ".$this->height."px;";
    }
}