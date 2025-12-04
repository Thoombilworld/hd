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
}
