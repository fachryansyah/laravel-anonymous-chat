<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Test</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
</head>
<body>
	
	<div class="jumbotron">
		<h1 class="display-4">Hello, world!</h1>
		<p class="lead">This is a simple anonymous chat app, made with laravel.</p>
	</div>

	<div class="container">
		<div id="chatList">
		</div>
	</div>
	
	<footer class="footer">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-10 col-10">
					<input type="text" id="message" class="form-control mt-2">	
				</div>
				<div class="col-md-2 col-2">
					<button id="sendMessage" class="btn btn-primary mb-1">Send</button>
				</div>
			</div>
		</div>
	</footer>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script type="text/javascript">
		const socket = new WebSocket('ws://localhost:9000');

		let chatList = $('#chatList');

		socket.addEventListener('message', function (event) {
			let resp = JSON.parse(event.data);
			chatList.append(`
				<div class="alert alert-primary">
					<p class="m-0"><b>${resp.from} : </b>${resp.message}</p>
				</div>
			`)
		    console.log(event.data);
		});

		$('#sendMessage').on('click', function(){
			sendMessageToServer($('#message').val())
		})

		function sendMessageToServer(msg){
			socket.send(msg);
			$('#message').val('')
		}
	</script>
</body>
</html>