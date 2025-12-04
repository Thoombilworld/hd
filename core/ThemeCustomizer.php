<?php
class HS_ThemeCustomizer
{
    public static function save(array $data): bool
    {
        $settingsPath = HS_THEME_PATH . '/settings.json';
        $current = [];
        if (file_exists($settingsPath)) {
            $json = json_decode(file_get_contents($settingsPath), true);
            if (is_array($json)) {
                $current = $json;
            }
        }
        $merged = array_merge($current, $data);
        return (bool)file_put_contents($settingsPath, json_encode($merged, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
