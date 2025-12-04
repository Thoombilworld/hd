<?php
class ThemeCustomizer
{
    private Theme $theme;
    private ColorEngine $colorEngine;

    public function __construct(Theme $theme, ColorEngine $colorEngine)
    {
        $this->theme = $theme;
        $this->colorEngine = $colorEngine;
    }

    public function load(string $folder): array
    {
        return $this->theme->loadThemeSettings($folder);
    }

    public function save(string $folder, array $settings): bool
    {
        $saved = $this->theme->saveThemeSettings($folder, $settings);
        if ($saved) {
            $css = $this->generateCustomCss($settings);
            $cssPath = HS_ROOT . '/themes/' . $folder . '/assets/css/custom-generated.css';
            if (is_dir(dirname($cssPath))) {
                file_put_contents($cssPath, $css);
            }
            $this->persistOptions($folder, $settings);
        }
        return $saved;
    }

    public function generateCustomCss(array $settings): string
    {
        $palette = $settings['palette'] ?? [];
        $typography = $settings['typography'] ?? [];
        $layout = $settings['layout'] ?? [];

        $vars = $this->colorEngine->generateCssVariables($palette);
        $font = $typography['font_family'] ?? "'Inter', system-ui, sans-serif";
        $size = $typography['base_size'] ?? '16px';
        $weight = $typography['weight'] ?? 500;
        $lineHeight = $typography['line_height'] ?? 1.6;
        $radius = $layout['card_radius'] ?? '16px';
        $container = $layout['container'] ?? '1200px';

        return ":root{${vars}--hg-radius:${radius};--hg-container:${container};}\n" .
            "body{font-family:${font};font-size:${size};line-height:${lineHeight};font-weight:${weight};}" .
            ".container{max-width:${container};margin:0 auto;padding:0 18px;}";
    }

    private function persistOptions(string $folder, array $settings): void
    {
        $db = function_exists('hs_db') ? hs_db() : null;
        if (!$db) {
            return;
        }
        $folderEsc = mysqli_real_escape_string($db, $folder);
        $valueEsc = mysqli_real_escape_string($db, json_encode($settings));
        $sql = "INSERT INTO hs_theme_options(theme_folder, option_key, option_value, updated_at)" .
            " VALUES('{$folderEsc}', 'settings', '{$valueEsc}', NOW())" .
            " ON DUPLICATE KEY UPDATE option_value='{$valueEsc}', updated_at=NOW()";
        mysqli_query($db, $sql);
    }
}
