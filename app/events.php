<?php

Cron::add('cron', '* * * * *', function() {
    Twitter::postTweet(array('status' => 'Tweet at '.Carbon::now()->format('G:ia'), 'format' => 'json'));
    return true;
}); 

?>