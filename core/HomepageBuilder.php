<?php
class HS_HomepageBuilder
{
    public function getBreakingNews(): array
    {
        return HS_DB::fetchAll("SELECT id,title,slug FROM hs_posts WHERE is_breaking=1 AND status='published' ORDER BY created_at DESC LIMIT 6");
    }

    public function getTopFeatured(): array
    {
        return HS_DB::fetchAll(
            "SELECT id,title,slug,image_main,excerpt,created_at,view_count FROM hs_posts WHERE is_featured=1 AND status='published' ORDER BY published_at DESC, created_at DESC LIMIT 6"
        );
    }

    public function getWorldHeroPosts(): array
    {
        return HS_DB::fetchAll(
            "SELECT id,title,slug,image_main,excerpt,created_at,view_count FROM hs_posts WHERE status='published' ORDER BY created_at DESC LIMIT 5"
        );
    }

    public function getIndiaTopStories(): array
    {
        return HS_DB::fetchAll(
            "SELECT p.id,p.title,p.slug,p.image_main,p.created_at FROM hs_posts p JOIN hs_categories c ON c.id=p.category_id WHERE c.slug='india' AND p.status='published' ORDER BY created_at DESC LIMIT 6"
        );
    }

    public function getKeralaMalayalamStories(): array
    {
        return HS_DB::fetchAll(
            "SELECT p.id,p.title,p.slug,p.image_main,p.created_at FROM hs_posts p JOIN hs_categories c ON c.id=p.category_id WHERE c.slug='kerala' AND p.status='published' ORDER BY created_at DESC LIMIT 6"
        );
    }

    public function getWorldByRegion(): array
    {
        $regions = ['asia','gcc','europe','americas','africa'];
        $data = [];
        foreach ($regions as $region) {
            $data[$region] = HS_DB::fetchAll(
                "SELECT id,title,slug,image_main,created_at FROM hs_posts WHERE region=? AND status='published' ORDER BY created_at DESC LIMIT 4",
                [$region]
            );
        }
        return $data;
    }

    public function getVideoHighlights(): array
    {
        return HS_DB::fetchAll(
            "SELECT id,title,slug,video_url,image_main,created_at FROM hs_posts WHERE type='video' AND status='published' ORDER BY created_at DESC LIMIT 4"
        );
    }

    public function getOpinionEditorials(): array
    {
        return HS_DB::fetchAll(
            "SELECT id,title,slug,excerpt,created_at FROM hs_posts WHERE region='opinion' AND status='published' ORDER BY created_at DESC LIMIT 4"
        );
    }

    public function getPhotoStories(): array
    {
        return HS_DB::fetchAll(
            "SELECT id,title,slug,image_main,created_at FROM hs_posts WHERE type='gallery' AND status='published' ORDER BY created_at DESC LIMIT 6"
        );
    }

    public function getTrendingTags(): array
    {
        return HS_DB::fetchAll("SELECT slug,name FROM hs_tags ORDER BY popularity DESC LIMIT 12");
    }

    public function getTrendingPosts(): array
    {
        return HS_DB::fetchAll(
            "SELECT id,title,slug,image_main,view_count,created_at FROM hs_posts WHERE status='published' ORDER BY view_count DESC LIMIT 8"
        );
    }

    public function getCategoryHighlights(): array
    {
        return HS_DB::fetchAll(
            "SELECT id,name,slug,description,color FROM hs_categories WHERE is_featured=1 OR slug IN ('politics','crime','education','religion') ORDER BY sort_order ASC, created_at DESC LIMIT 10"
        );
    }
}
