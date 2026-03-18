<?php
if (!defined('NO_ALONE')) exit;

class AdminSystem {

    /**
     * 1년 이상 지난 삭제 게시글 대상 통계
     */
    public static function getPurgeStats(): array {
        $board_count = (int)(sql_fetch("
            SELECT COUNT(*) AS cnt
            FROM community_free_board
            WHERE is_delete = 1
              AND delete_date IS NOT NULL
              AND delete_date <= DATE_SUB(NOW(), INTERVAL 1 YEAR)
        ")['cnt'] ?? 0);

        $comment_count = (int)(sql_fetch("
            SELECT COUNT(*) AS cnt
            FROM community_free_board_comment C
            INNER JOIN community_free_board B ON C.board_id = B.id
            WHERE B.is_delete = 1
              AND B.delete_date IS NOT NULL
              AND B.delete_date <= DATE_SUB(NOW(), INTERVAL 1 YEAR)
        ")['cnt'] ?? 0);

        $file_count = (int)(sql_fetch("
            SELECT COUNT(*) AS cnt
            FROM community_free_board_file F
            INNER JOIN community_free_board B ON F.board_id = B.id
            WHERE B.is_delete = 1
              AND B.delete_date IS NOT NULL
              AND B.delete_date <= DATE_SUB(NOW(), INTERVAL 1 YEAR)
        ")['cnt'] ?? 0);

        return [
            'board_count'   => $board_count,
            'comment_count' => $comment_count,
            'file_count'    => $file_count,
        ];
    }

    /**
     * 1년 이상 지난 삭제 게시글 영구 삭제
     * - 첨부 이미지 물리 파일 삭제
     * - community_free_board_file 하드 삭제
     * - community_free_board_comment 하드 삭제
     * - community_free_board 하드 삭제
     */
    public static function purgeExpiredBoards(): array {
        $deleted_boards   = 0;
        $deleted_comments = 0;
        $deleted_files    = 0;
        $deleted_physical = 0;

        // 대상 게시글 조회
        $result = sql_query("
            SELECT id
            FROM community_free_board
            WHERE is_delete = 1
              AND delete_date IS NOT NULL
              AND delete_date <= DATE_SUB(NOW(), INTERVAL 1 YEAR)
        ");

        while ($board = sql_fetch_array($result)) {
            $board_id = (int)$board['id'];

            // 1. 물리 파일 삭제 + community_free_board_file 하드 삭제
            $file_result = sql_query("
                SELECT file_guid, file_ext
                FROM community_free_board_file
                WHERE board_id = {$board_id}
            ");
            while ($file = sql_fetch_array($file_result)) {
                if (!empty($file['file_guid']) && !empty($file['file_ext'])) {
                    $physical_path = $_SERVER['DOCUMENT_ROOT']
                        . '/data/community_free_board/'
                        . $file['file_guid'] . '.' . $file['file_ext'];
                    if (is_file($physical_path)) {
                        @unlink($physical_path);
                        $deleted_physical++;
                    }
                }
            }
            $del_files = sql_query("DELETE FROM community_free_board_file WHERE board_id = {$board_id}");
            if ($del_files) $deleted_files += mysqli_affected_rows($GLOBALS['con']);

            // 2. 댓글 하드 삭제
            $del_comments = sql_query("DELETE FROM community_free_board_comment WHERE board_id = {$board_id}");
            if ($del_comments) $deleted_comments += mysqli_affected_rows($GLOBALS['con']);

            // 3. 게시글 하드 삭제
            sql_query("DELETE FROM community_free_board WHERE id = {$board_id}");
            $deleted_boards++;
        }

        return [
            'deleted_boards'   => $deleted_boards,
            'deleted_comments' => $deleted_comments,
            'deleted_files'    => $deleted_files,
            'deleted_physical' => $deleted_physical,
        ];
    }
}
