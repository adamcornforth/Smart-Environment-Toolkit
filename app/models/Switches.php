<?php 
class Switches extends Eloquent {
	public $timestamps = false;

    protected $table = 'Switch';

    public function spot()
    {
        return $this->belongsTo('Spot', 'spot_address', 'spot_address');
    }

    public function zone()
    {
        return $this->belongsTo('Zone');
    }
}