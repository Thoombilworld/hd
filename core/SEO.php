<?php
class HS_SEO
{
    public static function meta(array $meta = []): array
    {
        $settings = hs_settings();
        return [
            'title' => $meta['title'] ?? ($settings['site_title'] ?? 'HDSPTV'),
            'description' => $meta['description'] ?? ($settings['seo_meta_description'] ?? ''),
            'keywords' => $meta['keywords'] ?? ($settings['seo_meta_keywords'] ?? ''),
            'og_image' => $meta['og_image'] ?? ($settings['homepage_og_image'] ?? ''),
        ];
    }
}
