<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use RouterOS\Client;
use RouterOS\Exceptions\ClientException;

class testMikrotikHardcoded extends Controller
{

public function testMikrotikHardcoded()
{
    try {
        $client = new Client([
            'host' => '192.168.99.1',   // 👈 management IP
            'user' => 'admin',          // 👈 test user
            'pass' => 'qwertyui12.,',   // 👈 PLAIN password
            'port' => 8728,             // 👈 API port
            'timeout' => 10,
        ]);

        // Simple, safe command
        $identity = $client
            ->query('/system/identity/print')
            ->read();

        logger()->debug('MikroTik identity response', $identity);

        return response()->json([
            'success' => true,
            'identity' => $identity,
        ]);

    } catch (ClientException $e) {
        logger()->error('MikroTik API ClientException', [
            'error' => $e->getMessage(),        
        ]);

        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
        ], 500);

    } catch (\Throwable $e) {
        logger()->error('MikroTik API General Error', [
            'error' => $e->getMessage(),
        ]);

        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
        ], 500);
    }
}

}
