<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class Pagination
{
    public int $currentPage = 1;
    public int $pageSize = 10;
    public int $maxPages = 1;
    public string $loadUrl;
    public string $pageParamName = 'page';
    public string $perPageParamName = 'perPage';
    public int $maxPageLinks = 10;

    /**
     * @return array{
     *     display: int|string,
     *     link: string,
     *     active: bool,
     *     disabled: bool
     * }
     */
    public function getPages(): array
    {
        $pageArray = [];

        if ($this->maxPages > $this->maxPageLinks) {
            // First link
            $pageArray[] = $this->createPaginationItem(
                1,
                'First',
                false,
                $this->currentPage === 1
            );
        }

        // Previous link
        $pageArray[] = $this->createPaginationItem(
            $this->currentPage - 1,
            'Previous',
            false,
            $this->currentPage === 1
        );

        // All the individual page links
        for ($i = 1; $i <= $this->maxPages && $i <= $this->maxPageLinks; $i++) {
            $pageArray[] = $this->createPaginationItem($i);
        }

        if ($this->maxPages > $this->maxPageLinks) {
            // Add a ... for more pages
            $pageArray[] = $this->createPaginationItem(
                0,
                '...',
                false,
                true);
        }

        // Next link
        $pageArray[] = $this->createPaginationItem(
            $this->currentPage + 1,
            'Next',
            false,
            $this->currentPage === $this->maxPages
        );

        if ($this->maxPages > $this->maxPageLinks) {
            // Last link
            $pageArray[] = $this->createPaginationItem(
                $this->maxPages,
                'Last',
                false,
                $this->currentPage === $this->maxPages
            );
        }

        return $pageArray;
    }

    /**
     * @return array{
     *     display: int|string,
     *     link: string,
     *     active: bool,
     *     disabled: bool
     * }
     */
    private function createPaginationItem(
        int $page,
        ?string $display = null,
        ?bool $active = null,
        bool $disabled = false
    ): array {
        // Ensure page is in 1 to $this->maxPages value range.
        $page = max(1, min($page, $this->maxPages));

        $queryParams = [
            $this->pageParamName => $page,
            $this->perPageParamName => $this->pageSize,
        ];
        return [
            'display' => $display ?? $page,
            'link' => $this->loadUrl . '?' . http_build_query($queryParams),
            'active' => $active ?? $page === $this->currentPage,
            'disabled' => $disabled,
        ];
    }
}