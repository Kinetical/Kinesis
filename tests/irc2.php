<?php
set_time_limit(0);

class Bot
{
	var $server;
	var $nick;
	var $user;
	var $socket;

	function __construct( $server, $nick, $user )
	{
		$this->socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP); // Create the Socket
		$this->server = $server;
		$this->nick = $nick;
		$this->user = $user;
	}

	function __destruct()
	{
		socket_write($this->socket, "QUIT quit");
	}

	function connectBot()
	{
		socket_connect($this->socket, $this->server, 6667);
		socket_write($this->socket,"USER " . $this->user . " " . $this->user . " " . $this->user . " :" . $this->user . "\r\n"); // Send the Username to freenode
		socket_write($this->socket,"NICK " . $this->nick . " \r\n"); // Change our nickname
	}

	function joinChannel( $channel )
	{
		socket_write($this->socket, "JOIN " . $channel . " \r\n");
		flush();
	}

	function stayConnected()
	{
		while($data = socket_read($this->socket,2046)) // read whatever IRC is telling us
		{
			//echo $data;
			$message = explode(" ", $data);
			if($message[0] == "PING")
			{
				socket_write($this->socket, "PONG " . $message[1]);
				flush();
			}

	echo $data;
		}
	}
}

$bot = new Bot('irc.rizon.net','DIARRHEAAbot','diarrhea');
$bot->connectBot();
$bot->joinChannel('#vlsd');
$bot->stayConnected();