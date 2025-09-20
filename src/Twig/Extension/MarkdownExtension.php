<?php

namespace App\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class MarkdownExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('markdown_to_html', [$this, 'markdownToHtml'], ['is_safe' => ['html']]),
        ];
    }

    public function markdownToHtml(?string $content): string
    {
        if ($content === null) {
            return '';
        }

        // Convert Markdown to HTML
        $parsedown = new \Parsedown();
        $html = $parsedown->text($content);

        // Sanitize HTML
        $config = \HTMLPurifier_Config::createDefault();
        $purifier = new \HTMLPurifier($config);
        
        return $purifier->purify($html);
    }
}
