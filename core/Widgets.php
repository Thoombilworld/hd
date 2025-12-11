<?php
class Widgets
{
    private $db;

    public function __construct($db = null)
    {
        $this->db = $db ?: (function_exists('hs_db') ? hs_db() : null);
        $this->ensureTable();
    }

    private function ensureTable(): void
    {
        if (!$this->db) return;
        $sql = "CREATE TABLE IF NOT EXISTS hs_widgets (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            widget_area VARCHAR(80) NOT NULL,
            widget_type VARCHAR(80) NOT NULL,
            title VARCHAR(180) NULL,
            settings JSON NULL,
            sort_order INT UNSIGNED NOT NULL DEFAULT 0,
            active TINYINT(1) NOT NULL DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX(widget_area), INDEX(widget_type)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        mysqli_query($this->db, $sql);
    }

    public function areas(): array
    {
        return [
            'homepage_sidebar' => 'Homepage Sidebar',
            'homepage_body'    => 'Homepage Body',
            'article_sidebar'  => 'Article Sidebar',
            'footer'           => 'Footer',
        ];
    }

    public function types(): array
    {
        return [
            'html'        => 'Custom HTML',
            'trending'    => 'Trending posts',
            'newsletter'  => 'Newsletter form',
            'ad_slot'     => 'Ad Slot',
            'tag_cloud'   => 'Tag cloud',
        ];
    }

    public function list(string $area): array
    {
        if (!$this->db) {
            return [];
        }
        $area = mysqli_real_escape_string($this->db, $area);
        $sql = "SELECT * FROM hs_widgets WHERE widget_area='{$area}' AND active=1 ORDER BY sort_order ASC, id ASC";
        $res = mysqli_query($this->db, $sql);
        return $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
    }

    public function listAll(): array
    {
        if (!$this->db) return [];
        $res = mysqli_query($this->db, "SELECT * FROM hs_widgets ORDER BY widget_area ASC, sort_order ASC");
        return $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
    }

    public function save(array $data): bool
    {
        if (!$this->db) return false;
        $id      = (int)($data['id'] ?? 0);
        $area    = $data['widget_area'] ?? 'homepage_body';
        $type    = $data['widget_type'] ?? 'html';
        $title   = trim($data['title'] ?? '');
        $settings = $data['settings'] ?? [];
        $sort    = (int)($data['sort_order'] ?? 0);
        $active  = !empty($data['active']) ? 1 : 0;
        $settingsJson = json_encode($settings, JSON_UNESCAPED_SLASHES);

        if ($id > 0) {
            $stmt = mysqli_prepare($this->db, "UPDATE hs_widgets SET widget_area=?, widget_type=?, title=?, settings=?, sort_order=?, active=? WHERE id=? LIMIT 1");
            mysqli_stmt_bind_param($stmt, 'ssssiii', $area, $type, $title, $settingsJson, $sort, $active, $id);
            return mysqli_stmt_execute($stmt);
        }

        $stmt = mysqli_prepare($this->db, "INSERT INTO hs_widgets(widget_area, widget_type, title, settings, sort_order, active) VALUES(?,?,?,?,?,?)");
        mysqli_stmt_bind_param($stmt, 'ssssii', $area, $type, $title, $settingsJson, $sort, $active);
        return mysqli_stmt_execute($stmt);
    }

    public function delete(int $id): bool
    {
        if (!$this->db) return false;
        $stmt = mysqli_prepare($this->db, "DELETE FROM hs_widgets WHERE id=? LIMIT 1");
        mysqli_stmt_bind_param($stmt, 'i', $id);
        return mysqli_stmt_execute($stmt);
    }

    public function reorder(array $orderMap): void
    {
        if (!$this->db) return;
        foreach ($orderMap as $id => $sort) {
            $id = (int)$id;
            $sort = (int)$sort;
            $stmt = mysqli_prepare($this->db, "UPDATE hs_widgets SET sort_order=? WHERE id=? LIMIT 1");
            mysqli_stmt_bind_param($stmt, 'ii', $sort, $id);
            mysqli_stmt_execute($stmt);
        }
    }
}
