<?php 
class Zone extends Eloquent {
    protected $table = 'Zone';

    public function users() {
    	return User::all();
    }
}