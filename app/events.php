<?php
Event::listen('generic.event',function($client_data){
    return BrainSocket::message('generic.event',array('message'=>'A message from a generic event fired in Laravel!'));
});

Event::listen('app.zonechange',function($client_data){
    return BrainSocket::message('app.zonechange',array('message'=>'A zonechange occured!'));
});

Event::listen('app.heat',function($client_data){
    return BrainSocket::message('app.heat',array('message'=>'Heat data inserted'));
});

Event::listen('app.light',function($client_data){
    return BrainSocket::message('app.light',array('message'=>'Light data inserted'));
});

Event::listen('app.success',function($client_data){
    return BrainSocket::success(array('There was a Laravel App Success Event!'));
});

Event::listen('app.error',function($client_data){
    return BrainSocket::error(array('There was a Laravel App Error!'));
});