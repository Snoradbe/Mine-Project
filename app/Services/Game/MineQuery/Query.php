<?php


namespace App\Services\Game\MineQuery;


/*
 * Class written by xPaw
 *
 * Website: http://xpaw.me
 * GitHub: https://github.com/xPaw/PHP-Minecraft-Query
*/

use Truwork\Core\Exceptions\Exception;

class Query
{
    private const STATISTIC = 0x00;

    private const HANDSHAKE = 0x09;

    private $socket;

    private $players;

    private $info;

    private $ping;

    public function connect(string $ip, int $port = 25565, int $timeout = 3, bool $resolveSRV = true)
    {
        if(!is_int( $timeout ) || $timeout < 0) {
            throw new Exception( 'Timeout must be an integer.' );
        }

        if( $resolveSRV ) {
            $this->resolveSRV($ip, $port);
        }

        $microTime = microtime(true);

        $errno = $errStr = 0;
        $this->socket = @fsockopen('udp://' . $ip, $port, $errno, $errStr, $timeout);
        $this->ping = round((microtime(true) - $microTime) * 1000);
        if($errno || $this->socket === false) {
            throw new QueryException('Could not create socket: ' . $errStr);
        }

        stream_set_timeout($this->socket, $timeout);
        stream_set_blocking($this->socket, true);

        try {
            $challenge = $this->getChallenge();
            $this->getStatus($challenge);
        } finally {
            fclose($this->socket);
        }
    }

    public function getInfo()
    {
        return isset($this->info) ? $this->info : false;
    }

    public function getPlayers()
    {
        return isset($this->players) ? $this->players : false;
    }

    private function getChallenge()
    {
        $data = $this->writeData(self::HANDSHAKE);
        if($data === false) {
            throw new QueryException('Failed to receive challenge.');
        }

        return pack('N', $data);
    }

    private function getStatus($challenge)
    {
        $data = $this->writeData(self::STATISTIC, $challenge . pack('c*', 0x00, 0x00, 0x00, 0x00));
        if(!$data) {
            throw new QueryException('Failed to receive status.');
        }

        $last = '';
        $info = [];
        $data = substr( $data, 11 ); // splitnum + 2 int
        $data = explode( "\x00\x00\x01player_\x00\x00", $data );
        if( Count( $data ) !== 2 ) {
            throw new QueryException( 'Failed to parse server\'s response.' );
        }
        $players = substr($data[1], 0, -2);
        $data = explode("\x00", $data[0]);
        // Array with known keys in order to validate the result
        // It can happen that server sends custom strings containing bad things (who can know!)
        $keys = [
            'hostname'   => 'HostName',
            'gametype'   => 'GameType',
            'version'    => 'Version',
            'plugins'    => 'Plugins',
            'map'        => 'Map',
            'numplayers' => 'Players',
            'maxplayers' => 'MaxPlayers',
            'hostport'   => 'HostPort',
            'hostip'     => 'HostIp',
            'game_id'    => 'GameName'
        ];

        foreach ($data as $key => $value)
        {
            if (~$key & 1) {
                if (!array_key_exists($value, $keys)) {
                    $last = false;
                    continue;
                }

                $last = $keys[$value];
                $info[$last] = '';
            } elseif ($last != false) {
                $info[$last] = $value;
            }
        }

        // Ints
        $info['Ping'] = $this->ping;
        $info['Players'] = intval($info['Players']);
        $info['MaxPlayers'] = intval($info['MaxPlayers']);
        $info['HostPort'] = intval($info['HostPort']);
        // Parse "plugins", if any
        if ($info['Plugins']) {
            $data = explode(": ", $info['Plugins'], 2);
            $info['RawPlugins'] = $info['Plugins'];
            $info['Software'] = $data[0];
            if (count($data) == 2) {
                $info['Plugins'] = explode("; ", $data[1]);
            }
        } else {
            $info['Software'] = 'Vanilla';
        }

        $this->info = $info;
        if (empty($players)) {
            $this->players = null;
        } else {
            $this->players = explode("\x00", $players);
        }
    }

    private function writeData($command, $append = "" )
    {
        $command = pack('c*', 0xFE, 0xFD, $command, 0x01, 0x02, 0x03, 0x04) . $append;
        $length = strlen($command);
        if ($length !== fwrite($this->socket, $command, $length)) {
            throw new QueryException("Failed to write on socket.");
        }

        $data = fread($this->socket, 4096);
        if ($data === false) {
            throw new QueryException("Failed to read from socket.");
        }

        if (strlen($data) < 5 || $data[0] != $command[2]) {
            return false;
        }

        return substr($data, 5);
    }

    private function resolveSRV(&$address, &$port )
    {
        if (ip2long($address) !== false) {
            return;
        }

        $record = dns_get_record('_minecraft._tcp.' . $address, DNS_SRV);
        if (empty($record)) {
            return;
        }

        if (isset($record[0]['target'])) {
            $address = $record[0]['target'];
        }
    }

    public function getPing()
    {
        return $this->ping;
    }
}
