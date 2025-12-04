<?php
$builder = new HS_HomepageBuilder();
$breaking = $builder->getBreakingNews();
$hero = $builder->getWorldHeroPosts();
$india = $builder->getIndiaTopStories();
$kerala = $builder->getKeralaMalayalamStories();
$regions = $builder->getWorldByRegion();
$videos = $builder->getVideoHighlights();
$opinions = $builder->getOpinionEditorials();
$gallery = $builder->getPhotoStories();
$tags = $builder->getTrendingTags();
$view = __DIR__ . '/components/home/home.php';
include __DIR__ . '/layout.php';
