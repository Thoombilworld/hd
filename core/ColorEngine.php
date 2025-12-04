<?php
class HS_ColorEngine
{
    public static function palette(): array
    {
        $theme = new HS_Theme();
        $settings = $theme->settings();
        return $settings['palette'] ?? [
            'primary' => '#ff3b30',
            'secondary' => '#0d6efd',
            'surface' => '#0b0e11',
            'text' => '#e9ecef',
        ];
    }
}
