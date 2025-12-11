<?php
class ColorEngine
{
    public function generateCssVariables(array $palette): string
    {
        $defaults = [
            'primary' => '#1e88ff',
            'secondary' => '#6ee7ff',
            'background' => '#ffffff',
            'glass' => 'rgba(255,255,255,0.92)',
            'text' => '#0f172a',
            'muted' => '#475569',
            'neon' => '#5ef0ff',
            'dark' => '#0b1220',
        ];
        $colors = array_merge($defaults, $palette);
        $vars = '';
        foreach ($colors as $key => $value) {
            $vars .= "--hg-{$key}:{$value};";
        }
        return $vars;
    }

    public function generateGlassShadow(string $color): string
    {
        return "0 10px 40px rgba(" . $this->rgbaComponents($color, 0.28) . ")";
    }

    private function rgbaComponents(string $color, float $alpha): string
    {
        $hex = ltrim($color, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }
        $int = hexdec($hex);
        $r = ($int >> 16) & 255;
        $g = ($int >> 8) & 255;
        $b = $int & 255;
        return "{$r}, {$g}, {$b}, {$alpha}";
    }
}
