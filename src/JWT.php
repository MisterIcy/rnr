<?php


namespace MisterIcy\RnR;


class JWT
{
    private const JWT_SECRET = 'DYuSxgRsS9vx4Nxxe7vw';

    public static function createToken(array $payload): string
    {
        // Create and encode the header
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        // Encode the payload
        $payload = json_encode(
            [
                'exp' => time() + 600,
                'data' => $payload,
            ]);

        $b64Header = self::base64UrlEncode($header);
        $b64Payload = self::base64UrlEncode($payload);

        //Create signature hash
        $signature = hash_hmac('sha256', $b64Header.".".$b64Payload, self::JWT_SECRET, true);

        $b64Signature = self::base64UrlEncode($signature);

        return sprintf("%s.%s.%s", $b64Header, $b64Payload, $b64Signature);
    }

    public static function validateToken(string $token, ?array &$payload = null) : bool {
        $parts = explode('.', $token);
        $header = base64_decode($parts[0]);
        $tempPayload = base64_decode($parts[1]);
        $signature = base64_decode($parts[2]);

        $tempPayload = json_decode($tempPayload, true);

        // Validate the signature
        $tempSignature = hash_hmac('sha256', $parts[0].".".$parts[1], self::JWT_SECRET, true);
        if ($signature !== $tempSignature) {
            return false;
        }
        //(in)Sanity Check: Does the Token contain an expiration date?
        if (!array_key_exists('exp', $tempPayload)) {
            return false;
        }
        //Has the token expired?
        if ($tempPayload['exp'] < time()) {
            return false;
        }
        $payload = $tempPayload;
        return true;
    }

    private static function base64UrlEncode(string $data): string
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }
}
