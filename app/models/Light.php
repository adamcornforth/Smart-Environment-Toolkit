<?php 
class Light extends Eloquent {
	public $timestamps = false;

    protected $table = 'Light';

    public static function average($from, $to) {
        $readings = Light::whereBetween('created_at', array($from, $to))->get();
        $sum = 0; 
        foreach ($readings as $reading) {
            $sum += $reading->light_intensity; 
        }
        return ($sum > 0) ? number_format(($sum/$readings->count()), 2)."Ιv" : "--.--Ιv";
    }

    public static function lab() {
        $spot_addresses = array();
        $spots = Spot::getTowerSpots();

        // Get spot addresses from spots that have a job that uses the cell tower sensor
        foreach ($spots as $spot) 
            foreach ($spot->jobs as $job) 
                if($job->sensor->title == "Cell Tower") $spot_addresses[] = $spot->spot_address;

        // Get readings where cell tower spots have written the readings
        $readings = Light::whereIn('spot_address', $spot_addresses)->groupBy('spot_address')->orderBy('id', 'DESC')->take(3)->get();

        // Average the readings
        $sum = 0; 
        foreach ($readings as $reading) {
            echo $reading->light_intensity."<br />";
            $sum += $reading->light_intensity; 
        }
        
        return ($sum > 0) ? number_format(($sum/$readings->count()), 2)."Ιv" : "--.--Ιv";
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