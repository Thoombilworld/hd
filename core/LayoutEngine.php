<?php
class LayoutEngine
{
    public function render(string $templatePath, array $context = []): string
    {
        if (!is_readable($templatePath)) {
            return '';
        }
        extract($context, EXTR_SKIP);
        ob_start();
        include $templatePath;
        return (string)ob_get_clean();
    }

    public function renderBlocks(array $blocks, callable $renderer): string
    {
        $html = '';
        foreach ($blocks as $block) {
            $html .= $renderer($block);
        }
        return $html;
    }
}
