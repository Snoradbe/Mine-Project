<?php


namespace App\Services\Game\MineQuery;


/*
     * Queries Minecraft server
     * Returns array on success, false on failure.
     *
     * WARNING: This method was added in snapshot 13w41a (Minecraft 1.7)
     *
     * Written by xPaw
     *
     * Website: http://xpaw.me
     * GitHub: https://github.com/xPaw/PHP-Minecraft-Query
     *
     * ---------
     *
     * This method can be used to get server-icon.png too.
     * Something like this:
     *
     * $Server = new MinecraftPing( 'localhost' );
     * $Info = $Server->Query();
     * echo '<img width="64" height="64" src="' . Str_Replace( "\n", "", $Info[ 'favicon' ] ) . '">';
     *
*/
class Ping
{
    private $socket;

    private $serverAddress;

    private $serverPort;

    private $timeout;

    private $ping;

    public function __construct($address, $port = 25565, $timeout = 2, $resolveSRV = true)
    {
        $this->serverAddress = $address;
        $this->serverPort = (int)$port;
        $this->timeout = (int)$timeout;

        if ($resolveSRV) {
            $this->resolveSRV();
        }

        $this->connect();
    }

    public function __destruct()
    {
        $this->close();
    }

    public function close()
    {
        if ($this->socket !== null) {
            fclose($this->socket);
            $this->socket = null;
        }
    }

    public function connect()
    {
        $connectTimeout = $this->timeout;
        $microTime = microtime(true);
        $this->socket = @fsockopen($this->serverAddress, $this->serverPort, $errno, $errstr, $connectTimeout);
        $this->ping = round((microtime(true) - $microTime) * 1000);

        if (!$this->socket) {
            $this->socket = null;

            throw new PingException("Failed to connect or create a socket: $errno ($errstr)");
        }

        // Set Read/Write timeout
        stream_set_timeout($this->socket, $this->timeout);
    }

    public function query()
    {
        $timeStart = microtime(true); // for read timeout purposes

        // See http://wiki.vg/Protocol (Status Ping)
        $data = "\x00"; // packet ID = 0 (varint)

        $data .= "\x04"; // Protocol version (varint)
        $data .= pack('c', strlen($this->serverAddress)) . $this->serverAddress; // Server (varint len + UTF-8 addr)
        $data .= pack('n', $this->serverPort); // Server port (unsigned short)
        $data .= "\x01"; // Next state: status (varint)

        $data = pack('c', strlen($data)) . $data; // prepend length of packet ID + data

        fwrite($this->socket, $data); // handshake
        fwrite($this->socket, "\x01\x00"); // status ping

        $length = $this->readVarInt(); // full packet length

        if ($length < 10) {
            return false;
        }

        fgetc($this->socket); // packet type, in server ping it's 0

        $length = $this->readVarInt(); // string length

        $data = "";
        do {
            if (microtime(true) - $timeStart > $this->timeout) {
                throw new PingException('Server read timed out');
            }

            $remainder = $length - strlen($data);
            $block = fread($this->socket, $remainder); // and finally the json string
            // abort if there is no progress
            if (!$block) {
                throw new PingException('Server returned too few data');
            }

            $data .= $block;
        } while (strlen($data) < $length);

        if ($data === false) {
            throw new PingException('Server didn\'t return any data');
        }

        $data = json_decode($data, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            if (function_exists('json_last_error_msg')) {
                throw new PingException(json_last_error_msg());
            } else {
                throw new PingException('JSON parsing failed');
            }

            return false;
        }

        return $data;
    }

    public function queryOldPre17()
    {
        fwrite($this->socket, "\xFE\x01");
        $data = fread($this->socket, 512);
        $Len = strlen($data);

        if ($Len < 4 || $data[0] !== "\xFF") {
            return FALSE;
        }

        $data = substr($data, 3); // Strip packet header (kick message packet and short length)
        $data = iconv('UTF-16BE', 'UTF-8', $data);

        // Are we dealing with Minecraft 1.4+ server?
        if ($data[1] === "\xA7" && $data[2] === "\x31") {
            $data = explode("\x00", $data);

            return [
                'HostName' => $data[3],
                'Players' => intval($data[4]),
                'MaxPlayers' => intval($data[5]),
                'Protocol' => intval($data[1]),
                'Version' => $data[2],
                'Ping' => $this->ping
            ];
        }

        $data = explode("\xA7", $data);

        return [
            'HostName' => substr($data[0], 0, -1),
            'Players' => isset($data[1]) ? intval($data[1]) : 0,
            'MaxPlayers' => isset($data[2]) ? intval($data[2]) : 0,
            'Protocol' => 0,
            'Version' => '1.3',
            'Ping' => $this->ping
        ];
    }

    private function readVarInt()
    {
        $i = 0;
        $j = 0;

        while (true) {
            $k = @fgetc($this->socket);

            if ($k === FALSE) {
                return 0;
            }

            $k = Ord($k);

            $i |= ($k & 0x7F) << $j++ * 7;

            if ($j > 5) {
                throw new PingException('VarInt too big');
            }

            if (($k & 0x80) != 128) {
                break;
            }
        }

        return $i;
    }

    private function resolveSRV()
    {
        if (ip2long($this->serverAddress) !== false) {
            return;
        }

        $record = dns_get_record('_minecraft._tcp.' . $this->serverAddress, DNS_SRV);

        if (empty($record)) {
            return;
        }

        if (isset($record[0]['target'])) {
            $this->serverAddress = $record[0]['target'];
        }

        if (isset($record[0]['port'])) {
            $this->serverPort = $record[0]['port'];
        }
    }

    public function getPing()
    {
        return $this->ping;
    }
}
