<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class Pagination
{
    public string $path;

    public int $total;

    public int $current;

    public int $nearByPagesLimit = 4;

    private function getLink(int $page): string
    {
        return $this->path . $page;
    }

    /**
     * @return array{
     *  label: string,
     *  link: string,
     *  disabled: boolean,
     *  active: boolean
     * }
     */
    private function getPrevPageItem(): array
    {
        $item = [
            'label' => '&laquo;',
            'link' => $this->getLink($this->current - 1),
            'disabled' => false,
            'active' => false,
        ];

        if ($this->current <= 1) {
            $item['disabled'] = true;
        }

        return $item;
    }

    /**
     * @return array{
     *  label: string,
     *  link: string,
     *  disabled: boolean,
     *  active: boolean
     * }
     */
    private function getNextPageItem(): array
    {
        $item = [
            'label' => '&raquo;',
            'link' => $this->getLink($this->current + 1),
            'disabled' => false,
            'active' => false,
        ];

        if ($this->current >= $this->total) {
            $item['disabled'] = true;
        }

        return $item;
    }

    /**
     * @return array{
     *  label: string,
     *  link: string,
     *  disabled: boolean,
     *  active: boolean
     * }
     */
    private function getPageItem(int $page): array
    {
        return [
            'label' => strval($page),
            'link' => $this->getLink($page),
            'disabled' => $this->current === $page,
            'active' => $this->current === $page,
        ];
    }

    /**
     * @return array{
     *  label: string,
     *  link: string,
     *  disabled: boolean.
     *  active: false
     * }
     */
    private function getEllipseItem(): array
    {
        return [
            'label' => '...',
            'link' => '#',
            'disabled' => true,
            'active' => false,
        ];
    }

    public function getItems(): array
    {
        $items = [];

        // Always display the prev item
        $items[] = $this->getPrevPageItem();

        // Always display the first page
        $items[] = $this->getPageItem(1);

        $startIndex = 2;
        $endIndex = $this->total;

        if ($this->current > ($this->nearByPagesLimit + 1)) {
            $items[] = $this->getEllipseItem();
            if ($this->current < ($this->total - $this->nearByPagesLimit)) {
                $startIndex = $this->current - $this->nearByPagesLimit;
            } else {
                $startIndex = $this->total - ($this->nearByPagesLimit * 2);
            }
        }

        if (($this->current + $this->nearByPagesLimit) < $this->total) {
            $endIndex = $startIndex + ($this->nearByPagesLimit * 2);
        }

        for ($pageIndex = $startIndex; $pageIndex < $endIndex; $pageIndex++) {
            $items[] = $this->getPageItem($pageIndex);
        }

        if ($endIndex < $this->total) {
            $items[] = $this->getEllipseItem();
        }

        // Always display the last page
        $items[] = $this->getPageItem($this->total);

        // Always display the next page item
        $items[] = $this->getNextPageItem();

        return $items;
    }
}
