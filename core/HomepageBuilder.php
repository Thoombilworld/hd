<?php
class HomepageBuilder
{
    private $db;
    private array $config;

    public function __construct($db = null)
    {
        $this->db = $db ?: (function_exists('hs_db') ? hs_db() : null);
        $this->config = $this->loadConfig();
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    public function getBreakingNews(): array
    {
        $limit = (int)($this->config['breaking_limit'] ?? 6);
        $sql = "SELECT id, title, slug, category, region, image, excerpt, created_at, language FROM hs_posts WHERE status='published' AND (is_breaking=1 OR FIND_IN_SET('breaking', flags)) ORDER BY created_at DESC LIMIT ?";
        return $this->fetchPosts($sql, [$limit], 'i');
    }

    public function getWorldHeroPosts(): array
    {
        $limit = (int)($this->config['hero_limit'] ?? 5);
        $sql = "SELECT id, title, slug, category, region, image, excerpt, created_at, language FROM hs_posts WHERE status='published' AND (LOWER(category)='world' OR LOWER(region) IN ('world','asia','europe','gcc','americas','africa')) ORDER BY created_at DESC LIMIT ?";
        return $this->fetchPosts($sql, [$limit], 'i');
    }

    public function getIndiaTopStories(): array
    {
        $limit = (int)($this->config['india_limit'] ?? 6);
        $sql = "SELECT id, title, slug, category, region, image, excerpt, created_at, language FROM hs_posts WHERE status='published' AND (LOWER(category)='india' OR LOWER(region)='india') ORDER BY created_at DESC LIMIT ?";
        return $this->fetchPosts($sql, [$limit], 'i');
    }

    public function getKeralaMalayalamStories(): array
    {
        $limit = (int)($this->config['kerala_limit'] ?? 8);
        $mode = $this->config['kerala_language_mode'] ?? 'mixed';
        $sql = "SELECT id, title, slug, category, region, image, excerpt, created_at, language FROM hs_posts WHERE status='published' AND (LOWER(category) IN ('kerala','malayalam') OR LOWER(region) IN ('kerala','malayalam'))";
        $params = [];
        $types = '';
        if ($mode === 'en') {
            $sql .= " AND (language IS NULL OR language='' OR LOWER(language)='en')";
        } elseif ($mode === 'ml') {
            $sql .= " AND LOWER(language)='ml'";
        }
        $sql .= " ORDER BY created_at DESC LIMIT ?";
        $params[] = $limit;
        $types .= 'i';
        return $this->fetchPosts($sql, $params, $types);
    }

    public function getWorldByRegion(string $region): array
    {
        $limit = (int)($this->config['region_limit'] ?? 5);
        $sql = "SELECT id, title, slug, category, region, image, excerpt, created_at, language FROM hs_posts WHERE status='published' AND (LOWER(region)=? OR LOWER(category)=?) ORDER BY created_at DESC LIMIT ?";
        return $this->fetchPosts($sql, [$region, $region, $limit], 'ssi');
    }

    public function getOpinionEditorials(): array
    {
        $limit = (int)($this->config['opinion_limit'] ?? 4);
        $sql = "SELECT id, title, slug, category, region, image, excerpt, created_at, language, author FROM hs_posts WHERE status='published' AND (LOWER(category)='opinion' OR LOWER(category)='editorial') ORDER BY created_at DESC LIMIT ?";
        return $this->fetchPosts($sql, [$limit], 'i');
    }

    public function getVideoHighlights(): array
    {
        $limit = (int)($this->config['video_limit'] ?? 4);
        if (!$this->tableExists('hs_videos')) {
            return [];
        }
        $sql = "SELECT id, title, slug, thumbnail AS image, is_live, created_at FROM hs_videos WHERE status='published' ORDER BY created_at DESC LIMIT ?";
        return $this->fetchPosts($sql, [$limit], 'i');
    }

    public function getPhotoStories(): array
    {
        $limit = (int)($this->config['photo_limit'] ?? 6);
        if ($this->tableExists('hs_photos')) {
            $sql = "SELECT id, title, slug, image, created_at FROM hs_photos WHERE status='published' ORDER BY created_at DESC LIMIT ?";
            return $this->fetchPosts($sql, [$limit], 'i');
        }
        $sql = "SELECT id, title, slug, image, excerpt, created_at FROM hs_posts WHERE status='published' AND LOWER(category)='photo' ORDER BY created_at DESC LIMIT ?";
        return $this->fetchPosts($sql, [$limit], 'i');
    }

    public function getTrendingTags(): array
    {
        $limit = (int)($this->config['tags_limit'] ?? 12);
        if (!$this->tableExists('hs_tags')) {
            return [];
        }
        $sql = "SELECT id, name AS label, slug, usage_count FROM hs_tags ORDER BY usage_count DESC, name ASC LIMIT ?";
        return $this->fetchPosts($sql, [$limit], 'i');
    }

    public static function persistConfig(array $config): bool
    {
        $db = function_exists('hs_db') ? hs_db() : null;
        if (!$db) {
            return false;
        }

        $json = json_encode($config, JSON_UNESCAPED_SLASHES);
        $builder = new self($db);
        $builder->ensureSettingsTable();
        $escaped = mysqli_real_escape_string($db, $json);
        $now = date('Y-m-d H:i:s');
        $sql = "INSERT INTO hs_homepage_settings(id, config, updated_at) VALUES (1, '{$escaped}', '{$now}') ON DUPLICATE KEY UPDATE config='{$escaped}', updated_at='{$now}'";
        mysqli_query($db, $sql);

        $fallback = "INSERT INTO hs_settings(option_key, option_value) VALUES('homepage_config','{$escaped}') ON DUPLICATE KEY UPDATE option_value='{$escaped}'";
        mysqli_query($db, $fallback);
        return true;
    }

    private function fetchPosts(string $sql, array $params = [], string $types = ''): array
    {
        if (!$this->db || !$this->tableExistsFromQuery($sql)) {
            return [];
        }
        $stmt = mysqli_prepare($this->db, $sql);
        if (!$stmt) {
            return [];
        }
        if (!empty($params)) {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if (!$result) {
            mysqli_stmt_close($stmt);
            return [];
        }
        $rows = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        mysqli_stmt_close($stmt);
        return $rows;
    }

    private function tableExists(string $table): bool
    {
        if (!$this->db) {
            return false;
        }
        $table = mysqli_real_escape_string($this->db, $table);
        $sql = "SHOW TABLES LIKE '{$table}'";
        $res = mysqli_query($this->db, $sql);
        return $res && mysqli_num_rows($res) > 0;
    }

    private function tableExistsFromQuery(string $sql): bool
    {
        $tables = ['hs_posts', 'hs_videos', 'hs_photos', 'hs_tags'];
        foreach ($tables as $table) {
            if (stripos($sql, $table) !== false && !$this->tableExists($table)) {
                return false;
            }
        }
        return true;
    }

    private function loadConfig(): array
    {
        $defaults = [
            'hero_limit' => 5,
            'breaking_limit' => 8,
            'india_limit' => 6,
            'kerala_limit' => 8,
            'region_limit' => 5,
            'video_limit' => 4,
            'opinion_limit' => 4,
            'photo_limit' => 6,
            'tags_limit' => 12,
            'kerala_language_mode' => 'mixed',
            'show_hero_world' => 1,
            'show_india_focus' => 1,
            'show_kerala_malayalam' => 1,
            'show_world_sections' => 1,
            'show_videos_live' => 1,
            'show_opinion' => 1,
            'show_photo_gallery' => 1,
            'show_trending_tags' => 1,
            'show_newsletter' => 1,
            'show_breaking' => 1,
        ];

        $config = $this->loadFromHomepageSettings();
        if (!$config) {
            $config = $this->loadFromSettingsTable();
        }
        if (!is_array($config)) {
            $config = [];
        }
        return array_merge($defaults, $config);
    }

    private function loadFromHomepageSettings(): ?array
    {
        if (!$this->tableExists('hs_homepage_settings')) {
            return null;
        }
        $res = mysqli_query($this->db, "SELECT config FROM hs_homepage_settings WHERE id=1 LIMIT 1");
        if ($res && $row = mysqli_fetch_assoc($res)) {
            $decoded = json_decode($row['config'], true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }
        return null;
    }

    private function loadFromSettingsTable(): ?array
    {
        if (!$this->tableExists('hs_settings')) {
            return null;
        }
        $res = mysqli_query($this->db, "SELECT option_value FROM hs_settings WHERE option_key='homepage_config' LIMIT 1");
        if ($res && $row = mysqli_fetch_assoc($res)) {
            $decoded = json_decode($row['option_value'], true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }
        $legacy = mysqli_query($this->db, "SELECT `value` FROM hs_settings WHERE `key`='homepage_config' LIMIT 1");
        if ($legacy && $row = mysqli_fetch_assoc($legacy)) {
            $decoded = json_decode($row['value'], true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }
        return null;
    }

    private function ensureSettingsTable(): void
    {
        if (!$this->db) {
            return;
        }
        $sql = "CREATE TABLE IF NOT EXISTS hs_homepage_settings (" .
            "id INT PRIMARY KEY," .
            "config LONGTEXT," .
            "updated_at DATETIME" .
            ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        mysqli_query($this->db, $sql);
    }
}
