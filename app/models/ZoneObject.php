<?php 
class ZoneObject extends Elegant {
    protected $table = 'ZoneObject';

    protected $fillable = array('left', 'top', 'width', 'height', 'object_id', 'zone_id'); 

    public function object() {
    	return $this->belongsTo("Object");
    }

    public function getStyleAttribute() {
    	return "top: ".$this->top."px; left: ".$this->left."%; width: ".$this->width."%; height: ".$this->height."px;";
    }
}