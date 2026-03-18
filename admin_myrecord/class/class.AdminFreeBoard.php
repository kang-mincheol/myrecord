<?php
if (!defined('NO_ALONE')) exit;

class AdminFreeBoard {

    public static function getStats(): array {
        $total    = (int)(sql_fetch("SELECT COUNT(*) AS cnt FROM community_free_board WHERE is_delete = 0")['cnt'] ?? 0);
        $today    = (int)(sql_fetch("SELECT COUNT(*) AS cnt FROM community_free_board WHERE is_delete = 0 AND DATE(create_date) = CURDATE()")['cnt'] ?? 0);
        $deleted  = (int)(sql_fetch("SELECT COUNT(*) AS cnt FROM community_free_board WHERE is_delete = 1")['cnt'] ?? 0);
        $comments = (int)(sql_fetch("SELECT COUNT(*) AS cnt FROM community_free_board_comment WHERE is_delete = 0")['cnt'] ?? 0);

        return [
            'total'    => $total,
            'today'    => $today,
            'deleted'  => $deleted,
            'comments' => $comments,
        ];
    }

    private static function buildWhere(string $filterStatus, string $searchKey, string $searchVal): string {
        global $con;

        $where = ($filterStatus === 'deleted') ? "WHERE T1.is_delete = 1" : "WHERE T1.is_delete = 0";

        if ($searchVal !== '') {
            $safe = mysqli_real_escape_string($con, $searchVal);
            if ($searchKey === 'title') {
                $where .= " AND T1.title LIKE '%{$safe}%'";
            } elseif ($searchKey === 'contents') {
                $where .= " AND T1.contents LIKE '%{$safe}%'";
            } else {
                $where .= " AND A.user_nickname LIKE '%{$safe}%'";
            }
        }

        return $where;
    }

    public static function getTotalCount(string $filterStatus, string $searchKey, string $searchVal): int {
        $where = self::buildWhere($filterStatus, $searchKey, $searchVal);
        return (int)(sql_fetch("
            SELECT COUNT(*) AS cnt
            FROM community_free_board T1
            LEFT JOIN Account A ON T1.account_no = A.id
            {$where}
        ")['cnt'] ?? 0);
    }

    public static function getList(int $page, int $pageSize, string $filterStatus, string $searchKey, string $searchVal): array {
        $offset = ($page - 1) * $pageSize;
        $where  = self::buildWhere($filterStatus, $searchKey, $searchVal);
        $list   = [];

        $result = sql_query("
            SELECT T1.id, T1.title, T1.view_count, T1.create_date, T1.is_delete,
                   A.user_nickname,
                   (SELECT COUNT(*) FROM community_free_board_comment C
                    WHERE C.board_id = T1.id AND C.is_delete = 0) AS comment_count
            FROM community_free_board T1
            LEFT JOIN Account A ON T1.account_no = A.id
            {$where}
            ORDER BY T1.id DESC
            LIMIT {$offset}, {$pageSize}
        ");
        while ($row = sql_fetch_array($result)) {
            $list[] = $row;
        }
        return $list;
    }

    public static function getDetail(int $id): ?array {
        $id  = (int)$id;
        $row = sql_fetch("
            SELECT T1.id, T1.title, T1.contents, T1.view_count,
                   T1.create_date, T1.update_date, T1.is_delete,
                   A.user_nickname, A.id AS account_id
            FROM community_free_board T1
            LEFT JOIN Account A ON T1.account_no = A.id
            WHERE T1.id = {$id}
        ");
        return $row ?: null;
    }

    public static function getComments(int $boardId): array {
        $boardId = (int)$boardId;
        $list    = [];

        $result = sql_query("
            SELECT C.id, C.contents, C.create_datetime, C.is_delete,
                   A.user_nickname
            FROM community_free_board_comment C
            LEFT JOIN Account A ON C.account_no = A.id
            WHERE C.board_id = {$boardId}
            ORDER BY C.id ASC
        ");
        while ($row = sql_fetch_array($result)) {
            $list[] = $row;
        }
        return $list;
    }

    public static function deletePost(int $id): bool {
        $id = (int)$id;
        return (bool)sql_query("
            UPDATE community_free_board
            SET is_delete = 1, delete_date = NOW()
            WHERE id = {$id}
        ");
    }

    public static function restorePost(int $id): bool {
        $id = (int)$id;
        return (bool)sql_query("
            UPDATE community_free_board
            SET is_delete = 0, delete_date = NULL
            WHERE id = {$id}
        ");
    }

    public static function deleteComment(int $id): bool {
        $id = (int)$id;
        return (bool)sql_query("
            UPDATE community_free_board_comment
            SET is_delete = 1
            WHERE id = {$id}
        ");
    }
}
