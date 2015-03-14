<?php 
class Impact extends Eloquent {
	public $timestamps = false;

    protected $table = 'Impact';

    public function spot()
    {
        return $this->belongsTo('Spot', 'spot_address', 'spot_address');
    }

    public function zone()
    {
        return $this->belongsTo('Zone');
    }
}