<?php
class HS_DB
{
    private static ?mysqli $instance = null;

    public static function get(): mysqli
    {
        if (self::$instance === null) {
            require_once __DIR__ . '/../config/config.php';
            $link = hs_db();
            if (!$link) {
                throw new RuntimeException('Database connection unavailable');
            }
            self::$instance = $link;
        }
        return self::$instance;
    }

    public static function query(string $sql, array $params = []): mysqli_result|bool
    {
        $db = self::get();
        if (empty($params)) {
            return $db->query($sql);
        }
        $stmt = $db->prepare($sql);
        if (!$stmt) {
            throw new RuntimeException('Query prepare failed: ' . $db->error);
        }
        $types = '';
        $values = [];
        foreach ($params as $param) {
            $types .= is_int($param) ? 'i' : 's';
            $values[] = $param;
        }
        $stmt->bind_param($types, ...$values);
        $stmt->execute();
        return $stmt->get_result();
    }

    public static function fetchAll(string $sql, array $params = []): array
    {
        $result = self::query($sql, $params);
        if ($result === false) {
            return [];
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function fetchOne(string $sql, array $params = []): ?array
    {
        $result = self::query($sql, $params);
        if ($result === false) {
            return null;
        }
        $row = $result->fetch_assoc();
        return $row ?: null;
    }
}
