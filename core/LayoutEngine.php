<?php
class HS_LayoutEngine
{
    public static function render(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        include $view;
    }
}
