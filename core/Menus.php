<?php
class Menus
{
    private $db;

    public function __construct($db = null)
    {
        $this->db = $db ?: (function_exists('hs_db') ? hs_db() : null);
        $this->ensureTable();
    }

    private function ensureTable(): void
    {
        if (!$this->db) {
            return;
        }
        $sql = "CREATE TABLE IF NOT EXISTS hs_menus (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            location ENUM('header','footer','mobile','utility') NOT NULL DEFAULT 'header',
            label VARCHAR(150) NOT NULL,
            url VARCHAR(255) NOT NULL,
            sort_order INT UNSIGNED NOT NULL DEFAULT 0,
            visible TINYINT(1) NOT NULL DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX(location), INDEX(sort_order)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        mysqli_query($this->db, $sql);
    }

    public function allLocations(): array
    {
        return [
            'header' => 'Header',
            'footer' => 'Footer',
            'mobile' => 'Mobile',
            'utility' => 'Utility bar',
        ];
    }

    public function getMenuByLocation(string $location): array
    {
        if (!$this->db) {
            return [];
        }
        $loc = mysqli_real_escape_string($this->db, $location);
        $sql = "SELECT * FROM hs_menus WHERE location='{$loc}' AND visible=1 ORDER BY sort_order ASC, id ASC";
        $res = mysqli_query($this->db, $sql);
        return $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
    }

    public function getAll(): array
    {
        if (!$this->db) {
            return [];
        }
        $res = mysqli_query($this->db, "SELECT * FROM hs_menus ORDER BY location ASC, sort_order ASC");
        return $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
    }

    public function saveItem(array $item): bool
    {
        if (!$this->db) {
            return false;
        }
        $id       = (int)($item['id'] ?? 0);
        $location = $item['location'] ?? 'header';
        $label    = trim($item['label'] ?? '');
        $url      = trim($item['url'] ?? '');
        $sort     = (int)($item['sort_order'] ?? 0);
        $visible  = !empty($item['visible']) ? 1 : 0;

        if ($label === '' || $url === '') {
            return false;
        }

        if ($id > 0) {
            $stmt = mysqli_prepare($this->db, "UPDATE hs_menus SET location=?, label=?, url=?, sort_order=?, visible=? WHERE id=? LIMIT 1");
            mysqli_stmt_bind_param($stmt, 'sssiii', $location, $label, $url, $sort, $visible, $id);
            return mysqli_stmt_execute($stmt);
        }

        $stmt = mysqli_prepare($this->db, "INSERT INTO hs_menus(location,label,url,sort_order,visible) VALUES (?,?,?,?,?)");
        mysqli_stmt_bind_param($stmt, 'sssii', $location, $label, $url, $sort, $visible);
        return mysqli_stmt_execute($stmt);
    }

    public function deleteItem(int $id): bool
    {
        if (!$this->db) {
            return false;
        }
        $stmt = mysqli_prepare($this->db, "DELETE FROM hs_menus WHERE id=? LIMIT 1");
        mysqli_stmt_bind_param($stmt, 'i', $id);
        return mysqli_stmt_execute($stmt);
    }

    public function reorder(array $orderMap): void
    {
        if (!$this->db) {
            return;
        }
        foreach ($orderMap as $id => $position) {
            $id = (int)$id;
            $position = (int)$position;
            $stmt = mysqli_prepare($this->db, "UPDATE hs_menus SET sort_order=? WHERE id=? LIMIT 1");
            mysqli_stmt_bind_param($stmt, 'ii', $position, $id);
            mysqli_stmt_execute($stmt);
        }
    }
}
