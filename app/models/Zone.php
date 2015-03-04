<?php 
class Zone extends Elegant {
    protected $table = 'Zone';
    private $zones_width = 1138;  

    protected $fillable = array('left', 'top', 'width', 'height', 'object_id'); 

     /**
     * Override all() method to return zones with basestation user id of logged in user.
     * If logged in user is admin, return all zones 
     * @return [type] [description]
     */
    public static function all($columns = array()) {
        if(Auth::user()->isAdmin())
            return parent::all(); 
        
        $zones = DB::table('Zone')
            ->select('Zone.id as id')
            ->join('Object', 'Zone.object_id', '=', 'Object.id')
            ->join('Spot', 'Object.spot_id', '=', 'Spot.id')
            ->join('Basestation', 'Spot.basestation_id', '=', 'Basestation.id')
            ->join('Users', 'Users.id', '=', 'Basestation.user_id')
            ->where('Users.id', '=', Auth::user()->id)
            ->groupBy('Zone.id')
            ->get();
        $collection = new \Illuminate\Database\Eloquent\Collection;
        foreach ($zones as $zone) {
            $collection->add(Zone::find($zone->id));
        }
        
        return $collection;
    }


    /**
     * Override find() method to return zone with basestation user id of logged in user.
     * If logged in user is admin, return zone by id
     * @return [type] [description]
     */
    public static function find($id, $columns = array()) {
        if(Auth::user()->isAdmin())
            return parent::find($id); 

        $zone = Zone::whereId($id)->first(); 

        if(isset($zone->id) && $zone->object->spot->basestation->user_id == Auth::user()->id)
            return $zone; 
        else
            return false; 
    }

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