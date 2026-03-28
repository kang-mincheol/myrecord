<?php
if (!defined('NO_ALONE')) exit;

/**
 * JWT (JSON Web Token) 유틸리티
 * 외부 라이브러리 없이 PHP 내장 함수만 사용 (HS256)
 *
 * 토큰 종류:
 *  - Access Token  (mr_token  쿠키): 60분, API 인증용
 *  - Refresh Token (mr_refresh 쿠키): 30일, Access Token 갱신용
 */

// ── 인코딩 헬퍼 ──────────────────────────────────────────────────────────────

function jwt_base64url_encode(string $data): string {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function jwt_base64url_decode(string $data): string {
    $pad = 4 - strlen($data) % 4;
    if ($pad < 4) $data .= str_repeat('=', $pad);
    return base64_decode(strtr($data, '-_', '+/'));
}

// ── 핵심 함수 ─────────────────────────────────────────────────────────────────

/**
 * JWT 토큰 생성
 */
function jwt_encode(array $payload, string $secret): string {
    $header  = jwt_base64url_encode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
    $payload = jwt_base64url_encode(json_encode($payload));
    $sig     = jwt_base64url_encode(hash_hmac('sha256', "{$header}.{$payload}", $secret, true));
    return "{$header}.{$payload}.{$sig}";
}

/**
 * JWT 토큰 검증 및 디코드
 * 서명 불일치 또는 만료(exp) 시 null 반환
 */
function jwt_decode(string $token, string $secret): ?array {
    $parts = explode('.', $token);
    if (count($parts) !== 3) return null;

    [$header, $payload, $sig] = $parts;

    $expected = jwt_base64url_encode(hash_hmac('sha256', "{$header}.{$payload}", $secret, true));
    if (!hash_equals($expected, $sig)) return null;

    $data = json_decode(jwt_base64url_decode($payload), true);
    if (!is_array($data)) return null;

    // 만료 확인
    if (isset($data['exp']) && $data['exp'] < time()) return null;

    return $data;
}

// ── Access Token ──────────────────────────────────────────────────────────────

/**
 * Access Token 생성 (type: access, TTL: 60분)
 */
function jwt_create_access_token(int $account_id): string {
    return jwt_encode([
        'sub'  => $account_id,
        'type' => 'access',
        'iat'  => time(),
        'exp'  => time() + JWT_ACCESS_TTL,
    ], JWT_SECRET);
}

/**
 * Access Token을 httpOnly 쿠키로 설정
 */
function jwt_set_access_cookie(string $token): void {
    setcookie('mr_token', $token, [
        'expires'  => time() + JWT_ACCESS_TTL,
        'path'     => '/',
        'httponly' => true,
        'samesite' => 'Lax',
        'secure'   => IS_LIVE,
    ]);
}

// ── Refresh Token ─────────────────────────────────────────────────────────────

/**
 * Refresh Token 생성 (type: refresh, TTL: 30일)
 */
function jwt_create_refresh_token(int $account_id): string {
    return jwt_encode([
        'sub'  => $account_id,
        'type' => 'refresh',
        'iat'  => time(),
        'exp'  => time() + JWT_REFRESH_TTL,
    ], JWT_SECRET);
}

/**
 * Refresh Token을 httpOnly 쿠키로 설정
 */
function jwt_set_refresh_cookie(string $token): void {
    setcookie('mr_refresh', $token, [
        'expires'  => time() + JWT_REFRESH_TTL,
        'path'     => '/',
        'httponly' => true,
        'samesite' => 'Lax',
        'secure'   => IS_LIVE,
    ]);
}

// ── 공통 ──────────────────────────────────────────────────────────────────────

/**
 * 로그인 시 Access + Refresh 토큰 동시 발급
 * @return array{access: string, refresh: string}
 */
function jwt_issue_tokens(int $account_id): array {
    $access  = jwt_create_access_token($account_id);
    $refresh = jwt_create_refresh_token($account_id);
    jwt_set_access_cookie($access);
    jwt_set_refresh_cookie($refresh);
    jwt_save_refresh_token($account_id, $refresh);   // DB 저장
    return ['access' => $access, 'refresh' => $refresh];
}

/**
 * 로그아웃 시 두 쿠키 모두 삭제
 */
function jwt_clear_cookies(): void {
    $expired = ['expires' => time() - 3600, 'path' => '/', 'httponly' => true, 'samesite' => 'Lax', 'secure' => IS_LIVE];
    setcookie('mr_token',   '', $expired);
    setcookie('mr_refresh', '', $expired);
}

/**
 * 현재 요청에서 Access Token 문자열 추출
 * 우선순위: ① httpOnly 쿠키 → ② Authorization: Bearer 헤더
 */
function jwt_get_access_token(): string {
    if (!empty($_COOKIE['mr_token'])) {
        return $_COOKIE['mr_token'];
    }
    $auth = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
    if (substr($auth, 0, 7) === 'Bearer ') {
        return trim(substr($auth, 7));
    }
    return '';
}

/**
 * 현재 요청에서 Refresh Token 문자열 추출
 */
function jwt_get_refresh_token(): string {
    return $_COOKIE['mr_refresh'] ?? '';
}

// ── Refresh Token DB 관리 ─────────────────────────────────────────────────────

/**
 * Refresh Token을 DB에 저장 (SHA256 해시로 보관)
 */
function jwt_save_refresh_token(int $account_id, string $token): void {
    global $PDO;
    $hash       = hash('sha256', $token);
    $expires_at = date('Y-m-d H:i:s', time() + JWT_REFRESH_TTL);
    $PDO->execute(
        "Insert Into RefreshToken (account_id, token_hash, expires_at) Values (:aid, :hash, :exp)",
        [':aid' => $account_id, ':hash' => $hash, ':exp' => $expires_at]
    );
}

/**
 * Refresh Token이 DB에 유효하게 존재하는지 검증
 */
function jwt_verify_refresh_token(string $token): bool {
    global $PDO;
    $hash = hash('sha256', $token);
    $row  = $PDO->fetch(
        "Select id From RefreshToken Where token_hash = :hash And expires_at > Now()",
        [':hash' => $hash]
    );
    return !empty($row);
}

/**
 * 특정 Refresh Token을 DB에서 삭제 (로그아웃 / 토큰 rotation)
 */
function jwt_delete_refresh_token(string $token): void {
    global $PDO;
    $hash = hash('sha256', $token);
    $PDO->execute(
        "Delete From RefreshToken Where token_hash = :hash",
        [':hash' => $hash]
    );
}

/**
 * 특정 계정의 모든 Refresh Token 삭제 (강제 로그아웃)
 */
function jwt_delete_all_refresh_tokens(int $account_id): void {
    global $PDO;
    $PDO->execute(
        "Delete From RefreshToken Where account_id = :aid",
        [':aid' => $account_id]
    );
}

/**
 * 만료된 Refresh Token 일괄 정리 (선택적으로 주기 실행)
 */
function jwt_cleanup_expired_tokens(): void {
    global $PDO;
    $PDO->execute("Delete From RefreshToken Where expires_at <= Now()");
}
