<?php


namespace MisterIcy\RnR;


use MisterIcy\RnR\Exceptions\UnauthorizedException;

final class JWT
{
    /**
     * Creates a JSON Web Token
     * @param array<mixed> $payload The payload to be included into the token
     * @return string The Token
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
        $signature = hash_hmac('sha256', $b64Header.".".$b64Payload, $_ENV['APP_SECRET'], true);

        $b64Signature = self::base64UrlEncode($signature);

        return sprintf("%s.%s.%s", $b64Header, $b64Payload, $b64Signature);
    }

    /**
     * Helper for encoding
     * @param string $data
     * @return string
     */
    private static function base64UrlEncode(string $data): string
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }

    /**
     * Validates a Json Web Token
     * @param string $token The Token
     * @param array<mixed>|null $payload By reference parameter that exposes the payload of the token to the caller
     * @return bool true if the token is valid, otherwise an exception will be thrown
     * @throws \MisterIcy\RnR\Exceptions\UnauthorizedException
     */
    public static function validateToken(string $token, ?array &$payload = null): bool
    {
        $parts = explode('.', $token);
        $header = $parts[0];
        $tempPayload = $parts[1];
        $signature = $parts[2];

        // Validate the signature

        $tempSignature = hash_hmac('sha256', $header.".".$tempPayload, $_ENV['APP_SECRET'], true);

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
     * Helper for decoding
     * @param string $data
     * @return string
     */
    private static function base64UrlDecode(string $data): string
    {
        return str_replace(['-', '_', ''], ['+', '/', '='], base64_decode($data));
    }
}
