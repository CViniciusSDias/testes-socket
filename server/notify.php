<?php

$client = stream_socket_client('unix:///tmp/a.sock');
fwrite($client, json_encode($argv[1]));
fclose($client);
