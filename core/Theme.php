<?php
/**
 * Theme core for NEWS HDSPTV 2025 engine.
 */
class Theme
{
    private string $themesPath;
    private ?array $activeTheme = null;

    public function __construct(string $themesPath)
    {
        $this->themesPath = rtrim($themesPath, '/');
    }

    public function getActiveTheme(): array
    {
        if ($this->activeTheme !== null) {
            return $this->activeTheme;
        }

        $db = function_exists('hs_db') ? hs_db() : null;
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
        return $themes;
    }

    public function activateTheme(string $folder): bool
    {
        $info = $this->loadThemeInfo($folder);
        if (!$info) {
            return false;
        }

        $db = function_exists('hs_db') ? hs_db() : null;
        if ($db) {
            $folderEsc = mysqli_real_escape_string($db, $folder);
            $upsert = "INSERT INTO hs_settings(option_key, option_value) VALUES('theme_active', '{$folderEsc}') "
                . "ON DUPLICATE KEY UPDATE option_value='{$folderEsc}'";
            mysqli_query($db, $upsert);

            $legacyUpsert = "INSERT INTO hs_settings(`key`, `value`) VALUES('theme_active', '{$folderEsc}') "
                . "ON DUPLICATE KEY UPDATE `value`='{$folderEsc}'";
            mysqli_query($db, $legacyUpsert);
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
