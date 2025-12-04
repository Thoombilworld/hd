<?php
class HS_Helpers
{
    public static function slugify(string $text): string
    {
        $text = iconv('UTF-8', 'ASCII//TRANSLIT', $text);
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = trim($text, '-');
        $text = preg_replace('~[^-a-z0-9]+~i', '', $text);
        $text = strtolower($text);
        return $text ?: 'n-a';
    }

    public static function esc(?string $value): string
    {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }

    public static function json_response(array $data): void
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit;
    }

    public static function placeholder(int $w, int $h, string $label = 'HDSPTV'): string
    {
        $svg = sprintf(
            '<svg xmlns="http://www.w3.org/2000/svg" width="%d" height="%d" viewBox="0 0 %d %d" fill="none"><rect width="%d" height="%d" fill="#0f172a"/><text x="50%%" y="50%%" fill="#334155" font-size="%d" font-family="Inter" dominant-baseline="middle" text-anchor="middle">%s</text></svg>',
            $w,
            $h,
            $w,
            $h,
            $w,
            $h,
            max(16, (int)($w * 0.08)),
            htmlspecialchars($label, ENT_QUOTES, 'UTF-8')
        );

        return 'data:image/svg+xml;utf8,' . rawurlencode($svg);
    }
}
