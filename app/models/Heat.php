<?php 
class Heat extends Eloquent {
	public $timestamps = false;

    protected $table = 'Heat';

    public static function average($from, $to) {
        $readings = Heat::whereBetween('created_at', array($from, $to))->get();
        $sum = 0; 
        foreach ($readings as $reading) {
            $sum += $reading->heat_temperature; 
        }
        return ($sum > 0) ? number_format(($sum/$readings->count()), 2)."°C" : "--.--°C";
    }

    public static function lab() {
        $spot_addresses = array();
        $spots = Spot::getTowerSpots();

        // Get spot addresses from spots that have a job that uses the cell tower sensor
        foreach ($spots as $spot) 
            foreach ($spot->jobs as $job) 
                if($job->sensor->title == "Cell Tower") $spot_addresses[] = $spot->spot_address;

        // Get readings where cell tower spots have written the readings
        $readings = Heat::whereIn('spot_address', $spot_addresses)->groupBy('spot_address')->orderBy('id', 'DESC')->take(3)->get();

        // Average the readings
        $sum = 0; 
        foreach ($readings as $reading) { 
            echo $reading->heat_temperature." ($reading->spot_address)<br />";
            $sum += $reading->heat_temperature; 
        }
        
        return ($sum > 0) ? number_format(($sum/$readings->count()), 2)."°C" : "--.--°C";
    }

    public function spot()
    {
        return $this->belongsTo('Spot', 'spot_address', 'spot_address');
    }

    public function zone()
    {
        return $this->belongsTo('Zone');
    }
}