<?php

namespace App\Twig\Components;

use App\Service\HTTPHelper;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class Pagination
{
    use DefaultActionTrait;
    use ComponentToolsTrait;

    #[LiveProp(writable: true)]
    public int $currentPage = 1;

    #[LiveProp]
    public int $pageSize = 10;

    #[LiveProp]
    public int $maxPages = 1;

    #[LiveProp]
    public int $totalItems = 0;

    #[LiveProp]
    public string $pageParamName = 'page';

    #[LiveProp]
    public string $perPageParamName = 'perPage';

    #[LiveProp]
    public int $maxPageLinks = 10;

    #[LiveProp]
    public string $ellipsesText = '...';

    #[LiveProp]
    public string $previousText = 'Previous';

    #[LiveProp]
    public string $nextText = 'Next';

    #[LiveProp]
    public string $baseUrlLink = '';

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

        $maxPages = intval(ceil($this->totalItems / $this->pageSize));

        // Ensure the current page is in the range of valid pages
        $currentPage = max(1, min($this->currentPage, $maxPages));

        // Previous link
        $pageArray[] = $this->createPaginationItem($currentPage - 1, $this->previousText, false, 1 === $currentPage);

        // First page link
        $pageArray[] = $this->createPaginationItem(1, '1', 1 === $currentPage, false);

        // All the individual page links
        $startPageLink = $this->getStartPageLink($currentPage);
        $endPageLink = $this->getEndPageLink($currentPage);

        if ($startPageLink > 2) {
            $pageArray[] = $this->createPaginationItem(0, $this->ellipsesText, false, true);
        }

        for ($i = $startPageLink; $i <= $endPageLink; ++$i) {
            $pageArray[] = $this->createPaginationItem($i);
        }

        if ($endPageLink < $this->maxPages - 1) {
            $pageArray[] = $this->createPaginationItem(0, $this->ellipsesText, $currentPage === $maxPages, true);
        }

        // Last page link
        $pageArray[] = $this->createPaginationItem($maxPages, $maxPages, $currentPage === $maxPages, false);

        // Next link
        $pageArray[] = $this->createPaginationItem($currentPage + 1, $this->nextText, false, $currentPage === $maxPages);

        return $pageArray;
    }

    private function getStartPageLink(int $currentPage = 1): int
    {
        $startPageLink = 2;
        if ($currentPage > 4) {
            $startPageLink = $currentPage - 2;
        }
        if ($currentPage > $this->maxPages - 4) {
            $startPageLink = $this->maxPages - 6;
        }

        return $startPageLink;
    }

    private function getEndPageLink(int $currentPage = 10): int
    {
        $endPageLink = 7;
        if ($currentPage > 4) {
            $endPageLink = $currentPage + 2;
        }

        if ($currentPage > $this->maxPages - 5) {
            $endPageLink = $this->maxPages - 1;
        }

        return $endPageLink;
    }

    /**
     * @return array{
     *     page: int,
     *     display: int|string,
     *     link: string,
     *     active: bool,
     *     disabled: bool
     * }
     */
    private function createPaginationItem(
        int $page,
        string|int $display = null,
        bool $active = null,
        bool $disabled = false
    ): array {
        // Ensure page is in 1 to $this->maxPages value range.
        $page = max(1, min($page, $this->maxPages));

        $queryParams = "{$this->pageParamName}={$page}&{$this->perPageParamName}={$this->pageSize}";
        $url = HTTPHelper::buildUrl($this->baseUrlLink, ['query' => $queryParams], HTTPHelper::HTTP_URL_JOIN_QUERY);

        return [
            'page' => $page,
            'display' => $display ?? $page,
            'link' => $url,
            'active' => $active ?? $page === $this->currentPage,
            'disabled' => $disabled,
        ];
    }
}
