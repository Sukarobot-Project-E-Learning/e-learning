<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Log;

class JWTService
{
    private string $secretKey;
    private string $algorithm = 'HS256';
    private int $expiresIn = 86400; // 24 hours in seconds

    public function __construct()
    {
        $this->secretKey = config('app.key');
        $this->expiresIn = config('jwt.expires_in', 86400);
    }

    /**
     * Generate JWT token for user
     */
    public function generateToken(array $payload): string
    {
        $issuedAt = time();
        $expire = $issuedAt + $this->expiresIn;

        $tokenPayload = array_merge($payload, [
            'iat' => $issuedAt,
            'exp' => $expire,
            'iss' => config('app.url'),
        ]);

        return JWT::encode($tokenPayload, $this->secretKey, $this->algorithm);
    }

    /**
     * Decode and validate JWT token
     */
    public function decodeToken(string $token): ?object
    {
        try {
            return JWT::decode($token, new Key($this->secretKey, $this->algorithm));
        } catch (\Firebase\JWT\ExpiredException $e) {
            Log::warning('JWT token expired: ' . $e->getMessage());
            return null;
        } catch (\Firebase\JWT\SignatureInvalidException $e) {
            Log::warning('JWT signature invalid: ' . $e->getMessage());
            return null;
        } catch (\Exception $e) {
            Log::error('JWT decode error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate token for user model
     */
    public function generateTokenForUser($user): string
    {
        return $this->generateToken([
            'sub' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
            'role' => $user->role,
        ]);
    }

    /**
     * Get user ID from token
     */
    public function getUserIdFromToken(string $token): ?int
    {
        $decoded = $this->decodeToken($token);
        return $decoded?->sub ?? null;
    }

    /**
     * Generate refresh token (longer expiry)
     */
    public function generateRefreshToken(array $payload): string
    {
        $issuedAt = time();
        $expire = $issuedAt + (86400 * 7); // 7 days

        $tokenPayload = array_merge($payload, [
            'iat' => $issuedAt,
            'exp' => $expire,
            'iss' => config('app.url'),
            'type' => 'refresh',
        ]);

        return JWT::encode($tokenPayload, $this->secretKey, $this->algorithm);
    }

    /**
     * Validate refresh token
     */
    public function validateRefreshToken(string $token): ?object
    {
        $decoded = $this->decodeToken($token);
        
        if ($decoded && isset($decoded->type) && $decoded->type === 'refresh') {
            return $decoded;
        }
        
        return null;
    }
}
