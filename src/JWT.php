<?php


namespace MisterIcy\RnR;


use MisterIcy\RnR\Exceptions\UnauthorizedException;

final class JWT
{
    private const JWT_SECRET = 'DYuSxgRsS9vx4Nxxe7vw';

    /**
     * @param array<mixed> $payload
     * @return string
     */
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

    /**
     * @param string $data
     * @return string
     */
    private static function base64UrlEncode(string $data): string
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }

    /**
     * @param string $token
     * @param array<mixed>|null $payload
     * @return bool
     * @throws \MisterIcy\RnR\Exceptions\UnauthorizedException
     */
    public static function validateToken(string $token, ?array &$payload = null): bool
    {
        $parts = explode('.', $token);
        $header = $parts[0];
        $tempPayload = $parts[1];
        $signature = $parts[2];

        // Validate the signature

        $tempSignature = hash_hmac('sha256', $header.".".$tempPayload, self::JWT_SECRET, true);

        $tempSignature = self::base64UrlEncode($tempSignature);
        if ($signature !== $tempSignature) {
            throw new UnauthorizedException("Invalid token signature");
        }

        $tempPayload = json_decode(self::base64UrlDecode($tempPayload), true);
        //(in)Sanity Check: Does the Token contain an expiration date?
        if (!array_key_exists('exp', $tempPayload)) {
            throw new UnauthorizedException("Invalid token");
        }
        //Has the token expired?
        if ($tempPayload['exp'] < time()) {
            throw new UnauthorizedException("Token has expired");
        }
        $payload = $tempPayload;

        return true;
    }

    /**
     * @param string $data
     * @return string
     */
    private static function base64UrlDecode(string $data): string
    {
        return str_replace(['-', '_', ''], ['+', '/', '='], base64_decode($data));
    }
}
