<?php

namespace CViniciusSDias\WebSockets\Controller;

use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use SplObjectStorage;

class SampleController implements MessageComponentInterface
{
    private SplObjectStorage $clients;

    public function __construct()
    {
        $this->clients = new SplObjectStorage();
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);

        echo "Nova conexão de {$conn->resourceId}" . PHP_EOL;
    }

    public function onClose(ConnectionInterface $conn)
    {
        echo 'Fechando ' . PHP_EOL;
        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        echo 'Recebendo mensagem de ' . $from->resourceId;

        foreach ($this->clients as $client) {
            if ($client !== $from) $client->send(json_encode(['type' => 'chat', 'text' => $msg]));
        }
    }

    public function broadCast($msg)
    {
        foreach ($this->clients as $client) {
            $client->send($msg);
        }
    }
}
