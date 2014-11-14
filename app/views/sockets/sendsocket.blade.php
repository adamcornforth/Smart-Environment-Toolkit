@extends('layouts.master')

@section('meta')
	@parent
	<script type="text/javascript" src="{{ asset('js/hammer.js')}}"></script>
	<script type="text/javascript" src="{{ asset('js/jquery.hammer.js')}}"></script>
	<script type="text/javascript" src="{{ asset('js/brainsocket.min.js') }}" /></script>
	<script type="text/javascript" src="{{ asset('js/touch.js')}}"></script>

@stop
@section('content')
<script>
	/**
     * Brainsocket listeners
     */
    window.app = {};

    var baseUrl = $("body").data('bs-base-url');

    var socket = new WebSocket('ws://' + baseUrl + ':8080');

    app.BrainSocket = new BrainSocket(
            socket,
            new BrainSocketPubSub()
    );

    setTimeout(sendMessage("{{ $socket }}"), 0);

    function sendMessage(msg){
	    // Wait until the state of the socket is not ready and send the message when it is...
	    waitForSocketConnection(socket, function(){
	        console.log("Message sent!");
	        app.BrainSocket.message('app.{{ $socket }}',{ 'event' : '{{ $socket }}'});
	    });
	}

	// Make the function wait until the connection is made...
	function waitForSocketConnection(socket, callback){
	    setTimeout(
	        function () {
	            if (app.BrainSocket.connection.readyState === 1) {
	                console.log("Connection ready!")
	                if(callback != null){
	                    callback();
	                }
	                return;

	            } else {
	                console.log("Waiting for connection...")
	                waitForSocketConnection(socket, callback);
	            }

	        }, 1); // wait 5 milisecond for the connection...
	}
</script>
@stop