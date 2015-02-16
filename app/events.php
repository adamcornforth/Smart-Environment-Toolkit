<?php

// Every min
if(!function_exists("contains")) { 
	Cron::setDisablePreventOverlapping();

	// Every hour 
	Cron::add('cronhour', '0 * * * *', function() {
		$heat = Heat::average(Carbon::now()->subHour()->toDateTimeString(), Carbon::now()->toDateTimeString());
		$light = Light::average(Carbon::now()->subHour()->toDateTimeString(), Carbon::now()->toDateTimeString());
	    return Twitter::postTweet(array('status' => Carbon::now()->format('D jS M g:ia').': Average Lab Temp: '.$heat.', Lab Light: '.$light, 'format' => 'json'));
	    return true;
	}); 

	
	function contains($str, array $arr) {
	    foreach($arr as $a) {
	        if (stripos($str,$a) !== false) return true;
	    }
	    return false; 
	}


	Cron::add('cronmin', '* * * * *', function() {
	    $mentions = Twitter::getMentionsTimeline();

	    $tweet = null; 

		foreach ($mentions as $mention) {
			if(Tweet::whereTweetId($mention->id)->get()->count() == 0 && $mention->user->screen_name != "SccSmartLab") {
				echo $mention->text."<br />";
				$tweet = Tweet::create(array(
								'tweet_id' => $mention->id,
								'tweet' => $mention->text, 
								'from' => $mention->user->screen_name, 
								'seen' => 1,
								'replied' => 1
							)); 

				// Reply with most recent heat 
				if(contains($mention->text, array('heat', 'temp', 'hot', 'warm', 'cool'))) {
					$tweet = Twitter::postTweet(array('status' => 'Hi @'.$mention->user->screen_name.', the average heat of the lab now is '.Heat::lab().' ('.Carbon::now()->format('g:ia').')', 'format' => 'json', 'in_reply_to_status_id' => $mention->id)); 

				// Reply with most recent light
				} elseif(contains($mention->text, array('light', 'bright', 'dark'))) {
					$tweet = Twitter::postTweet(array('status' => 'Hi @'.$mention->user->screen_name.', the average light of the lab now is '.Light::lab().' ('.Carbon::now()->format('g:ia').')', 'format' => 'json', 'in_reply_to_status_id' => $mention->id)); 
				} else {
					// Reply with most recent light
					$tweet = Twitter::postTweet(array('status' => 'Hi @'.$mention->user->screen_name.', sorry - we could not recognise your request.', 'format' => 'json', 'in_reply_to_status_id' => $mention->id)); 
				}

			} else {
				echo "Tweet already read<br />";
			}
		}

	    return $tweet."\n";
	});

} 

?>