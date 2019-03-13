<?php

namespace App\Http\Controllers;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Illuminate\Http\Request;

class WebSocketController extends Controller implements MessageComponentInterface
{
    private $connections = [];

    public function onOpen(ConnectionInterface $conn)
    {
    	$this->connections[$conn->resourceId] = [
    		"conn" => $conn
    	];
    }

    public function onClose(ConnectionInterface $conn)
    {
    	$disconnectId = $conn->resourceId;
    	unset($this->connections[$disconnectId]);
    	foreach ($this->connections as $connection) {
    		$connection->conn->send(json_encode([
    			'offline_user' => $disconnectId
    		]));
    	}
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
    	$userId = $this->connections[$conn->resourceId];
     	echo "An error has occurred with user: {$e->getMessage()}";
     	unset($this->connections[$conn->resourceId]);
     	$conn->close();
    }

    public function onMessage(ConnectionInterface $conn, $msg)
    {
    	foreach ($this->connections as $resourceId => $connection) {
    		$connection['conn']->send(json_encode([
    			"from" => $conn->resourceId,
    			"message" => $msg
    		]));
    	}
    }
}
