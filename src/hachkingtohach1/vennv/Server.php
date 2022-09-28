<?php

namespace hachkingtohach1\vennv;

use hachkingtohach1\vennv\utils\PacketUtil;
use hachkingtohach1\vennv\threads\MainThread;

final class Server{

    public const SERVER_PREFIX = "Proxy>";

    private static string $ip = '127.0.01';
    private static int $port = 19132;
    private MainThread $thread;

    public static function getInstance() : Server{
        return new self;
    }
    
    public function run() : void{
        echo "\e[1;33;40m You are running VennVProxy! \e[0m\n\n\n";

        echo "\e[0;33;40m Type >IP< for proxy: \e[0m";

        fscanf(STDIN, "%s", $ip);
        self::$ip = $ip;

        echo "\e[0;33;40m Type >PORT< for proxy: \e[0m";

        fscanf(STDIN, "%s", $port);
        self::$port = $port;

        echo "\e[1;34;40m
            __      __          __      _______                     
            \ \    / /          \ \    / /  __ \                    
             \ \  / /__ _ __  _ _\ \  / /| |__) | __ _____  ___   _ 
              \ \/ / _ \ '_ \| '_ \ \/ / |  ___/ '__/ _ \ \/ / | | |
               \  /  __/ | | | | | \  /  | |   | | | (_) >  <| |_| |
                \/ \___|_| |_|_| |_|\/   |_|   |_|  \___/_/\_\\__,  |
                                                            __/    |
                                                            |_____/
        \e[0m";
        echo "\e[1;32;40m Listening at: ".self::$ip.":".self::$port." \e[0m\n\n\n".self::SERVER_PREFIX." \n";
        $this->thread = new MainThread();
        $this->thread->start();
        $this->listen();
    }

    public function send() : void{
        $host = "127.0.0.1";
		$port = 19132;
        $socket = @fsockopen('udp://'.$host, $port, $errno, $errstr, 4);
		stream_set_timeout($socket, 4);
		stream_set_blocking($socket, true);
		$command = "1, VennV";
		$length = \strlen($command);
		fwrite($socket, $command, $length);
		fread($socket, 4096);
		fclose($socket);
    }

    public function listen() : void{
        $host = self::$ip;
        $port = self::$port;
        set_time_limit(0);
        $socket = socket_create(AF_INET, SOCK_DGRAM, 0) or die("Could not create socket\n");
        socket_bind($socket, $host, $port) or die("Could not bind to socket\n");
        socket_recvfrom($socket, $buf, 512, 0, $remote_ip, $remote_port);
        echo self::SERVER_PREFIX." \e[1;32;40m Sending from: \e[0m \e[0;31;43m$remote_ip : $remote_port\e[0m \e[1;32;40m -->\e[0m \e[1;30;40m" . $buffer = $buf. "\e[0m\n";      
        socket_close($socket);
        PacketUtil::stringToPacket($buffer);
        $this->send();
        $this->listen();
    }

    public function getIp() : string{
        return self::$ip;
    }

    public function getPort() : int{
        return self::$port;
    }

    public function getThread() : MainThread{
        return $this->thread;
    }
}