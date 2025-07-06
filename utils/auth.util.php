<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Ensure session is started
}

/**
 * Check if a user is authenticated.
 *
 * @return bool
 */
function isAuthenticated(): bool {
    return isset($_SESSION['user']);
}

/**
 * Get the currently authenticated user's data.
 *
 * @return array|null
 */
function getCurrentUser(): ?array {
    return $_SESSION['user'] ?? null;
}
