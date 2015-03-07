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

	protected $fillable = array('first_name', 'last_name', 'email', 'password');

	public static function seederCreate(array $data) 
	{
		return parent::create($data);
	}

	public static function create(array $data) 
	{
		if(Input::has('first_name') && Input::has('last_name') && Input::has('password') && Input::has('email')) {
			if(isset($data['password'])) {
				if($data['password']) 
					$data['password'] = Hash::make($data['password']);
				else
					array_pull($data, 'password');
			}
			return parent::create($data);
		} else {
			return false; 
		}
	}

	public function update(array $data = array()) 
	{
		if(isset($data['password'])) {
			if($data['password']) 
				$data['password'] = Hash::make($data['password']);
			else
				array_pull($data, 'password');
		}
		return parent::update($data);
	}

	/**
	 * Returns if user is admin
	 * @return boolean [description]
	 */
	public function isAdmin() 
	{
		return $this->admin;
	}

	/**
	 * Get this user's spots 
	 */
	public function spots()
	{
		return $this->hasMany('Spot');
	}

	/**
	 * Get this user's basestation 
	 */
	public function basestation()
	{
		return $this->hasOne('Basestation');
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
