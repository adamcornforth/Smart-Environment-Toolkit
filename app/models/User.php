<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'Users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

	/**
	 * Returns if user is admin
	 * @return boolean [description]
	 */
	public function isAdmin() {
		return $this->admin;
	}

	/**
	 * Get this user's spots 
	 */
	public function spots()
	{
		return $this->hasMany('Spot');
	}

	public function getNameAttribute()
	{
		return $this->first_name." ".$this->last_name;
	}

	/**
	 * Returns users that don't have basestations assigned
	 */
	public static function getUsersNoBasestation() 
	{
        $collection = new \Illuminate\Database\Eloquent\Collection;
        foreach (User::all() as $user) {
        	if(!isset(Basestation::whereUserId($user->id)->first()->id) && !$user->isAdmin())
        		$collection->add($user);
        }
        
        return $collection->reverse();
	}

}
