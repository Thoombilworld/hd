<?php
class HS_Theme
{
    private string $activeTheme;
    private string $themesDir;

    public function __construct(string $themesDir = __DIR__ . '/../themes')
    {
        $this->themesDir = $themesDir;
        $this->activeTheme = $this->detectActiveTheme();
        $this->defineConstants();
    }

    private function detectActiveTheme(): string
    {
        if (!empty($_SESSION['preview_theme'])) {
            return $_SESSION['preview_theme'];
        }
        $settings = hs_settings();
        $theme = $settings['active_theme'] ?? 'liquid_glass_2025';
        $candidatePath = $this->themesDir . '/' . $theme;
        if (is_dir($candidatePath) && file_exists($candidatePath . '/layout.php')) {
            return $theme;
        }
        return 'liquid_glass_2025';
    }

    private function defineConstants(): void
    {
        if (!defined('HS_THEME')) {
            define('HS_THEME', $this->activeTheme);
        }
        if (!defined('HS_THEME_PATH')) {
            define('HS_THEME_PATH', $this->themesDir . '/' . HS_THEME);
        }
        if (!defined('HS_THEME_URL')) {
            define('HS_THEME_URL', HS_BASE_URL . 'themes/' . HS_THEME . '/');
        }
    }

    public function metadata(): array
    {
        $path = HS_THEME_PATH . '/metadata.json';
        if (!file_exists($path)) {
            return [];
        }
        $json = json_decode(file_get_contents($path), true);
        return is_array($json) ? $json : [];
    }

    public function settings(): array
    {
        $path = HS_THEME_PATH . '/settings.json';
        if (!file_exists($path)) {
            return [];
        }
        $json = json_decode(file_get_contents($path), true);
        return is_array($json) ? $json : [];
    }
}
