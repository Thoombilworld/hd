<?php
class HS_ThemeInstaller
{
    public static function verify(string $zipPath): bool
    {
        return file_exists($zipPath) && mime_content_type($zipPath) === 'application/zip';
    }
}
