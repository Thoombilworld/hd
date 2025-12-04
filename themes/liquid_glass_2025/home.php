<?php
$builder = new HS_HomepageBuilder();
$breaking = $builder->getBreakingNews();
$featured = $builder->getTopFeatured();
$hero = $builder->getWorldHeroPosts();
$india = $builder->getIndiaTopStories();
$kerala = $builder->getKeralaMalayalamStories();
$regions = $builder->getWorldByRegion();
$videos = $builder->getVideoHighlights();
$opinions = $builder->getOpinionEditorials();
$gallery = $builder->getPhotoStories();
$tags = $builder->getTrendingTags();
$trending = $builder->getTrendingPosts();
$categoryHighlights = $builder->getCategoryHighlights();
$view = __DIR__ . '/components/home/home.php';
include __DIR__ . '/layout.php';
