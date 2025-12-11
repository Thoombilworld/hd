<?php
/**
 * Theme core for NEWS HDSPTV 2025 engine.
 */
class Theme
{
    private string $themesPath;
    private ?array $activeTheme = null;
    private $db;

    public function __construct(string $themesPath)
    {
        $this->themesPath = rtrim($themesPath, '/');
        $this->db = function_exists('hs_db') ? hs_db() : null;
        $this->ensureThemeTable();
    }

    private function ensureThemeTable(): void
    {
        if (!$this->db) {
            return;
        }
        $sql = "CREATE TABLE IF NOT EXISTS hs_themes (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            theme_name VARCHAR(150) NOT NULL,
            theme_folder VARCHAR(150) NOT NULL UNIQUE,
            version VARCHAR(50) NULL,
            author VARCHAR(100) NULL,
            screenshot VARCHAR(255) NULL,
            is_pro TINYINT(1) NOT NULL DEFAULT 0,
            has_customizer TINYINT(1) NOT NULL DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        mysqli_query($this->db, $sql);
    }

    public function getActiveTheme(): array
    {
        if ($this->activeTheme !== null) {
            return $this->activeTheme;
        }

        $db = $this->db;
        $activeFolder = null;
        if ($db) {
            $result = mysqli_query($db, "SELECT option_value FROM hs_settings WHERE option_key='theme_active' LIMIT 1");
            if ($result && $row = mysqli_fetch_assoc($result)) {
                $activeFolder = $row['option_value'];
            }

            if (!$activeFolder) {
                $legacy = mysqli_query($db, "SELECT `value` FROM hs_settings WHERE `key`='theme_active' LIMIT 1");
                if ($legacy && $row = mysqli_fetch_assoc($legacy)) {
                    $activeFolder = $row['value'];
                }
            }
        }
        if (!$activeFolder) {
            $activeFolder = 'liquid_glass_2025';
        }

        $this->activeTheme = $this->loadThemeInfo($activeFolder);
        if (!$this->activeTheme) {
            $this->activeTheme = $this->loadThemeInfo('liquid_glass_2025');
        }

        return $this->activeTheme;
    }

    public function getThemes(): array
    {
        $themes = [];
        if (!is_dir($this->themesPath)) {
            return $themes;
        }
        $items = scandir($this->themesPath);
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $info = $this->loadThemeInfo($item);
            if ($info) {
                $themes[] = $info;
            }
        }
        $this->syncThemesToDb($themes);
        return $themes;
    }

    public function activateTheme(string $folder): bool
    {
        $info = $this->loadThemeInfo($folder);
        if (!$info) {
            return false;
        }

        $db = $this->db;
        if ($db) {
            $folderEsc = mysqli_real_escape_string($db, $folder);
            $upsert = "INSERT INTO hs_settings(option_key, option_value) VALUES('theme_active', '{$folderEsc}') "
                . "ON DUPLICATE KEY UPDATE option_value='{$folderEsc}'";
            mysqli_query($db, $upsert);

            $legacyUpsert = "INSERT INTO hs_settings(`key`, `value`) VALUES('theme_active', '{$folderEsc}') "
                . "ON DUPLICATE KEY UPDATE `value`='{$folderEsc}'";
            mysqli_query($db, $legacyUpsert);

            $this->registerThemeRecord($info);
        }
        $this->activeTheme = $info;
        return true;
    }

    public function getThemeInfo(string $folder): ?array
    {
        return $this->loadThemeInfo($folder);
    }

    public function loadThemeSettings(string $folder): array
    {
        $settingsFile = $this->themesPath . '/' . $folder . '/settings.json';
        if (!is_readable($settingsFile)) {
            return [];
        }
        $json = file_get_contents($settingsFile);
        $data = json_decode($json, true);
        return is_array($data) ? $data : [];
    }

    public function saveThemeSettings(string $folder, array $settings): bool
    {
        $settingsFile = $this->themesPath . '/' . $folder . '/settings.json';
        if (!is_dir(dirname($settingsFile))) {
            return false;
        }
        $encoded = json_encode($settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        return (bool)file_put_contents($settingsFile, $encoded);
    }

    public function getThemeRecords(): array
    {
        if (!$this->db) {
            return [];
        }
        $res = mysqli_query($this->db, "SELECT * FROM hs_themes ORDER BY created_at DESC");
        return $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
    }

    public function syncThemesToDb(array $themes = null): void
    {
        if (!$this->db) {
            return;
        }
        $themes = $themes ?? $this->getThemes();
        foreach ($themes as $theme) {
            $this->registerThemeRecord($theme);
        }
    }

    private function registerThemeRecord(array $theme): void
    {
        if (!$this->db) {
            return;
        }
        $stmt = mysqli_prepare($this->db, "INSERT INTO hs_themes(theme_name, theme_folder, version, author, screenshot, is_pro, has_customizer)
            VALUES(?,?,?,?,?,?,?) ON DUPLICATE KEY UPDATE theme_name=VALUES(theme_name), version=VALUES(version), author=VALUES(author), screenshot=VALUES(screenshot), is_pro=VALUES(is_pro), has_customizer=VALUES(has_customizer)");
        $name = $theme['name'] ?? ($theme['folder'] ?? '');
        $folder = $theme['folder'] ?? '';
        $version = $theme['version'] ?? '';
        $author = $theme['author'] ?? '';
        $shot = $theme['screenshot'] ?? '';
        $isPro = !empty($theme['is_pro']) ? 1 : 0;
        $custom = !empty($theme['has_customizer']) ? 1 : 0;
        mysqli_stmt_bind_param($stmt, 'ssssiii', $name, $folder, $version, $author, $shot, $isPro, $custom);
        mysqli_stmt_execute($stmt);
    }

    private function loadThemeInfo(string $folder): ?array
    {
        $metaFile = $this->themesPath . '/' . $folder . '/metadata.json';
        if (!is_readable($metaFile)) {
            return null;
        }
        $data = json_decode((string)file_get_contents($metaFile), true);
        if (!is_array($data)) {
            return null;
        }
        $data['folder'] = $data['folder'] ?? $folder;
        $data['path'] = $this->themesPath . '/' . $folder;
        $data['has_customizer'] = !empty($data['has_customizer']);
        return $data;
    }
}
