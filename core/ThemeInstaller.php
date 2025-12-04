<?php
class ThemeInstaller
{
    private string $themesPath;

    public function __construct(string $themesPath)
    {
        $this->themesPath = rtrim($themesPath, '/');
    }

    public function installFromZip(string $zipPath): array
    {
        if (!class_exists('ZipArchive')) {
            return ['success' => false, 'message' => 'ZipArchive not available'];
        }
        if (!is_readable($zipPath)) {
            return ['success' => false, 'message' => 'Package not found'];
        }

        $zip = new ZipArchive();
        if ($zip->open($zipPath) !== true) {
            return ['success' => false, 'message' => 'Unable to open theme package'];
        }

        $tempDir = $this->themesPath . '/_tmp_' . uniqid();
        mkdir($tempDir, 0755, true);
        $zip->extractTo($tempDir);
        $zip->close();

        $metaFile = $this->findMetadata($tempDir);
        if (!$metaFile) {
            $this->rrmdir($tempDir);
            return ['success' => false, 'message' => 'metadata.json missing'];
        }
        $meta = json_decode((string)file_get_contents($metaFile), true);
        if (!is_array($meta) || empty($meta['folder'])) {
            $this->rrmdir($tempDir);
            return ['success' => false, 'message' => 'Invalid metadata'];
        }

        $target = $this->themesPath . '/' . basename($meta['folder']);
        $this->rrmdir($target);
        rename(dirname($metaFile), $target);
        $this->rrmdir($tempDir);
        $this->registerTheme($meta);

        return ['success' => true, 'message' => 'Theme installed', 'folder' => $meta['folder']];
    }

    private function findMetadata(string $base): ?string
    {
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($base));
        foreach ($iterator as $file) {
            if ($file->getFilename() === 'metadata.json') {
                return $file->getPathname();
            }
        }
        return null;
    }

    private function registerTheme(array $meta): void
    {
        $db = function_exists('hs_db') ? hs_db() : null;
        if (!$db) {
            return;
        }
        $folder = mysqli_real_escape_string($db, $meta['folder']);
        $name = mysqli_real_escape_string($db, $meta['name'] ?? $meta['folder']);
        $version = mysqli_real_escape_string($db, $meta['version'] ?? '1.0.0');
        $author = mysqli_real_escape_string($db, $meta['author'] ?? '');
        $screenshot = mysqli_real_escape_string($db, $meta['screenshot'] ?? '');
        $isPro = !empty($meta['is_pro']) ? 1 : 0;
        $hasCustomizer = !empty($meta['has_customizer']) ? 1 : 0;

        $sql = "INSERT INTO hs_themes(theme_name, theme_folder, version, author, screenshot, is_pro, has_customizer, created_at, updated_at)"
            . " VALUES('{$name}', '{$folder}', '{$version}', '{$author}', '{$screenshot}', {$isPro}, {$hasCustomizer}, NOW(), NOW())"
            . " ON DUPLICATE KEY UPDATE version='{$version}', author='{$author}', screenshot='{$screenshot}', is_pro={$isPro}, has_customizer={$hasCustomizer}, updated_at=NOW()";
        mysqli_query($db, $sql);
    }

    private function rrmdir(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }
        $items = array_diff(scandir($dir), ['.', '..']);
        foreach ($items as $item) {
            $path = $dir . '/' . $item;
            if (is_dir($path)) {
                $this->rrmdir($path);
            } else {
                unlink($path);
            }
        }
        rmdir($dir);
    }
}
