<?php

namespace Modules\Documents\Services;

use League\CommonMark\Exception\CommonMarkException;
use League\CommonMark\GithubFlavoredMarkdownConverter;

/**
 * Class DocumentRenderService
 */
class DocumentRenderService
{
    /**
     * Render markdown to HTML.
     *
     * @param string $markdown
     * @return string
     * @throws CommonMarkException
     */
    public function render(string $markdown): string
    {
        $converter = new GithubFlavoredMarkdownConverter();

        return (string) $converter->convert($markdown);
    }
}
