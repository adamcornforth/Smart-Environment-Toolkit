<?php 
class ZoneObject extends Elegant {
    protected $table = 'ZoneObject';

    protected $fillable = array('left', 'top', 'width', 'height', 'object_id', 'zone_id'); 

    /**
     * Override find() method to return zone with basestation user id of logged in user.
     * If logged in user is admin, return zone by id
     * @return [type] [description]
     */
    public static function find($id, $columns = array()) {
        if(Auth::user()->isAdmin())
            return parent::find($id); 

        $zone_object = ZoneObject::whereId($id)->first(); 

        if(isset($zone_object->id) && isset($zone_object->object->spot->id) && $zone_object->object->spot->basestation->user_id == Auth::user()->id)
            return $zone_object; 
        else
            return false; 
    }

    public function object() {
    	return $this->belongsTo("Object");
    }

    public function getStyleAttribute() {
    	return "top: ".$this->top."px; left: ".$this->left."%; width: ".$this->width."%; height: ".$this->height."px;";
    }
}