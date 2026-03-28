<?php
include_once($_SERVER["DOCUMENT_ROOT"] . "/api/common.php");

header("Content-Type: application/json; charset=UTF-8");

// /api/v1 prefix 이후의 경로만 추출
$uri    = strtok($_SERVER['REQUEST_URI'], '?');
$prefix = '/api/v1';
$path   = '/' . ltrim(substr($uri, strlen($prefix)), '/');
$method = $_SERVER['REQUEST_METHOD'];

/**
 * 라우트 정의: [HTTP_METHOD, URL_패턴, 컨트롤러명, 액션명]
 *
 * 주의: 구체적인 경로(예: /records/me)를 파라미터 경로(예: /records/{id}) 보다 반드시 앞에 위치시켜야 함
 */
$routes = [
    // -------------------------
    // Auth
    // -------------------------
    ['POST',   '/auth/login',                        'Auth',        'login'],
    ['POST',   '/auth/logout',                       'Auth',        'logout'],

    // -------------------------
    // Accounts
    // -------------------------
    ['GET',    '/accounts/me',                       'Accounts',    'getMe'],
    ['POST',   '/accounts',                          'Accounts',    'create'],
    ['PUT',    '/accounts/me/password',              'Accounts',    'changePassword'],
    ['PUT',    '/accounts/me',                       'Accounts',    'updateMe'],

    // -------------------------
    // Boards (자유게시판)
    // -------------------------
    ['GET',    '/boards',                            'Boards',      'list'],
    ['POST',   '/boards',                            'Boards',      'create'],
    ['GET',    '/boards/{id}/comments',              'Boards',      'listComments'],
    ['POST',   '/boards/{id}/comments',              'Boards',      'createComment'],
    ['DELETE', '/boards/{id}/comments/{cid}',        'Boards',      'deleteComment'],
    ['GET',    '/boards/{id}/edit',                  'Boards',      'editData'],
    ['GET',    '/boards/{id}',                       'Boards',      'view'],
    ['PUT',    '/boards/{id}',                       'Boards',      'update'],
    ['DELETE', '/boards/{id}',                       'Boards',      'delete'],

    // -------------------------
    // Records (마이레코드) - 구체적 경로 우선
    // -------------------------
    ['GET',    '/records/me',                        'Records',     'getMe'],
    ['GET',    '/records/ranking/total',             'Records',     'rankingTotal'],
    ['GET',    '/records/ranking',                   'Records',     'ranking'],
    ['GET',    '/records',                           'Records',     'list'],
    ['POST',   '/records',                           'Records',     'create'],
    ['GET',    '/records/{id}/certificate',          'Records',     'certificate'],
    ['GET',    '/records/{id}/verify',               'Records',     'verify'],
    ['GET',    '/records/{id}/edit',                 'Records',     'editData'],
    ['GET',    '/records/{id}/comments',             'Records',     'listComments'],
    ['POST',   '/records/{id}/comments',             'Records',     'createComment'],
    ['DELETE', '/records/{id}/comments/{cid}',       'Records',     'deleteComment'],
    ['GET',    '/records/{id}',                      'Records',     'view'],
    ['DELETE', '/records/{id}',                      'Records',     'delete'],

    // -------------------------
    // Workout Logs (득근일지) - 구체적 경로 우선
    // -------------------------
    ['GET',    '/workout-logs/calendar',             'WorkoutLogs',   'calendar'],
    ['GET',    '/workout-logs',                      'WorkoutLogs',   'list'],
    ['POST',   '/workout-logs',                      'WorkoutLogs',   'create'],
    ['GET',    '/workout-logs/{id}',                 'WorkoutLogs',   'view'],
    ['PUT',    '/workout-logs/{id}',                 'WorkoutLogs',   'update'],
    ['DELETE', '/workout-logs/{id}',                 'WorkoutLogs',   'delete'],

    // -------------------------
    // Admin - Dashboard
    // -------------------------
    ['GET',    '/admin/dashboard',                   'AdminDashboard','index'],

    // -------------------------
    // Admin - Accounts (회원 관리)
    // -------------------------
    ['GET',    '/admin/accounts',                    'AdminAccounts', 'list'],

    // -------------------------
    // Admin - Records (기록 관리) - 구체적 경로 우선
    // -------------------------
    ['GET',    '/admin/records',                     'AdminRecords',  'list'],
    ['PUT',    '/admin/records/{id}/status',         'AdminRecords',  'updateStatus'],
    ['GET',    '/admin/records/{id}',                'AdminRecords',  'view'],

    // -------------------------
    // Admin - Boards (자유게시판 관리) - 구체적 경로 우선
    // -------------------------
    ['GET',    '/admin/boards',                      'AdminBoards',   'list'],
    ['DELETE', '/admin/boards/{id}/comments/{cid}',  'AdminBoards',   'deleteComment'],
    ['POST',   '/admin/boards/{id}/restore',         'AdminBoards',   'restore'],
    ['GET',    '/admin/boards/{id}',                 'AdminBoards',   'view'],
    ['DELETE', '/admin/boards/{id}',                 'AdminBoards',   'delete'],

    // -------------------------
    // Admin - Access Logs (접속 로그)
    // -------------------------
    ['GET',    '/admin/access-logs',                 'AdminAccessLogs','list'],

    // -------------------------
    // Admin - System (시스템 관리) - 구체적 경로 우선
    // -------------------------
    ['GET',    '/admin/system/purge-stats',          'AdminSystem',   'purgeStats'],
    ['DELETE', '/admin/system/expired-boards',       'AdminSystem',   'purgeExpiredBoards'],
];

/**
 * URL 패턴과 실제 경로를 매칭하고 경로 파라미터를 추출
 * 매칭 실패 시 null 반환
 */
function matchRoute(string $pattern, string $path): ?array {
    preg_match_all('/\{([a-zA-Z_]+)\}/', $pattern, $param_names);
    $regex = preg_replace('/\{[a-zA-Z_]+\}/', '([^/]+)', $pattern);
    $regex = '#^' . $regex . '$#';

    if (!preg_match($regex, $path, $matches)) {
        return null;
    }

    array_shift($matches);
    return empty($param_names[1]) ? [] : array_combine($param_names[1], $matches);
}

foreach ($routes as [$route_method, $pattern, $controller, $action]) {
    if ($route_method !== $method) {
        continue;
    }

    $path_params = matchRoute($pattern, $path);
    if ($path_params === null) {
        continue;
    }

    $file = __DIR__ . "/controllers/{$controller}Controller.php";
    if (!file_exists($file)) {
        http_response_code(500);
        echo json_encode(["code" => "SERVER_ERROR", "msg" => "컨트롤러를 찾을 수 없습니다"], JSON_UNESCAPED_UNICODE);
        exit;
    }

    require_once $file;
    $class = $controller . 'Controller';
    $class::$action($path_params);
    exit;
}

http_response_code(404);
echo json_encode(["code" => "NOT_FOUND", "msg" => "요청한 API를 찾을 수 없습니다"], JSON_UNESCAPED_UNICODE);
