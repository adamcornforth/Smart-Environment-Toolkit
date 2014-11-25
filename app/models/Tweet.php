<?php 
class Tweet extends Eloquent {
	public $timestamps = false;

    protected $table = 'Tweet';
    protected $fillable = array('tweet_id', 'tweet', 'from', 'from_url', 'seen', 'replied');
}