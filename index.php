<?php
$session = mt_rand(1,999);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Chat</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width">
	<script src="js/jquery.js" type="text/javascript"></script>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<title>Chat</title>
	<style type="text/css">
	* {margin:0;padding:0;box-sizing:border-box;font-family:arial,sans-serif;resize:none;}
	html,body {width:100%;height:100%;}
	#wrapper {position:relative;margin:auto;max-width:1000px;height:100%; border: 2px solid #000000; border-radius: 25px;}
	#chat_output {position:absolute;top:25px;left:0;padding:20px;width:100%;height:calc(100% - 100px);}
	#chat_input {position:absolute;bottom:0;left:0;padding:10px;width:100%;height:100px;border:1px solid #ccc; margin-bottom: 20px;}
	#online{
		border: 2px solid #000000;
		border-radius: 20px;
		height: 100%;
	}
	#onlines{
		border: 2px solid #000000;
		border-radius: 20px;
		height: 100%;
		width: 100%;
		margin-left: 20px;

	}
</style>
</head>
<body>
	<div id="wrapper">
		<div class="form-inline" style="margin-top: 25px;">
			<div class="form-group">
				<input type="text" class="form-control" id="nome" placeholder="Digite seu nome" >
				<button type="button" class="btn btn-primary" id="conectar" style="margin-left: 10px;">Conectar</button>
				<button type="button" class="btn btn-secondary" style="margin-left: 10px;" id="desconectar" disabled="">Desconectar</button>
			</div>
		</div>

		<div class="row">
			<div class="col-8">
				<div id="chat_output"></div>
			</div>
			<div class="col-3" id="onlines" >
				<center><h5><strong>Onlines</strong></h5></center>
				<div id="nomeOnline"></div>
			</div>
		</div>
		<textarea  class="form-control"id="chat_input" placeholder="Digite sua mensagem..." disabled=""></textarea>
		<script type="text/javascript">
			jQuery(function($){
			// Websocket
			var websocket_server = new WebSocket("ws://localhost:8080/");
			websocket_server.onopen = function(e) {
				websocket_server.send(
					JSON.stringify({
						'type':'socket',
						'user_id':<?php echo $session; ?>
					})
					);
			};
			websocket_server.onerror = function(e) {
				// Errorhandling
			}
			websocket_server.onmessage = function(e)
			{
				var json = JSON.parse(e.data);
				switch(json.type) {
					case 'chat':
					$('#chat_output').append(json.msg);
					break;
				}
			}
			// Events
			var usuario;

			$('#conectar').on('click',function(e){
				$("#conectar").prop("disabled", true);
				$("#nome").prop("disabled", true);
				$("#desconectar").prop("disabled", false);
				$("#chat_input").prop("disabled", false);
				usuario = $('#nome').val();
				
			});

			$('#desconectar').on('click',function(e){
				$("#conectar").prop("disabled", false);
				$("#nome").prop("disabled", false);
				$("#desconectar").prop("disabled", true);
				$("#chat_input").prop("disabled", true);
				usuario = $('#nome').val();
				
			});
			$('#chat_input').on('keyup',function(e){
				if(e.keyCode==13 && !e.shiftKey)
				{
					var chat_msg = $(this).val();
					usuario = $('#nome').val();
					websocket_server.send(
						JSON.stringify({
							'type':'chat',
							'user_id':usuario,
							'chat_msg':chat_msg
						})
						);
					$(this).val('');
				}
			});
			





		});
	</script>
</div>

</body>
</html>