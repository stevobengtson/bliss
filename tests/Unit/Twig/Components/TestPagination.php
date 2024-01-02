<?php

namespace App\Tests\Unit\Twig\Components;

use App\Twig\Components\Pagination;
use PHPUnit\Framework\TestCase;

class TestPagination extends TestCase
{
    /**
     * @return array<
     *     string,
     *     array{
     *       currentPage: int,
     *       pageSize: int,
     *       maxPages: int,
     *       maxPageLinks: int
     *     }
     * >
     */
    public static function paginationTestData(): array
    {
        return [
            'First page selected' => [
                'currentPage' => 1,
                'pageSize' => 10,
                'maxPages' => 65,
                'maxPageLinks' => 10,
                'expected' => [
                    'pages' => [ 'Previous', '1', '2', '3', '4', '5', '6', '7', '...', '65', 'Next'],
                    'disabled' => [ true, false, false, false, false, false, false, false, true, false, false],
                    'active' => 1,
                ],
            ],
            'Last page selected' => [
                'currentPage' => 65,
                'pageSize' => 10,
                'maxPages' => 65,
                'maxPageLinks' => 10,
                'expected' => [
                    'pages' => [ 'Previous', '1', '...', '59', '60', '61', '62', '63', '64', '65', 'Next'],
                    'disabled' => [ false, false, true, false, false, false, false, false, false, false, true],
                    'active' => 9,
                ],
            ],
            'Middle page selected' => [
                'currentPage' => 32,
                'pageSize' => 10,
                'maxPages' => 65,
                'maxPageLinks' => 10,
                'expected' => [
                    'pages' => [ 'Previous', '1', '...', '30', '31', '32', '33', '34', '...', '65', 'Next'],
                    'disabled' => [ false, false, true, false, false, false, false, false, true, false, false],
                    'active' => 5,
                ],
            ],
        ];
    }

    /**
     * @param int $currentPage
     * @param int $pageSize
     * @param int $maxPages
     * @param int $maxPageLinks
     * @param array{
     *     pages: array<string>,
     *     state: array<int>,
     *     active: int
     * } $expected
     *
     * @dataProvider paginationTestData
     *
     * @return void
     */
    public function testGetPages(
        int $currentPage,
        int $pageSize,
        int $maxPages,
        int $maxPageLinks,
        array $expected
    ): void
    {
        $pagination = new Pagination();
        $pagination->currentPage = $currentPage;
        $pagination->pageSize = $pageSize;
        $pagination->maxPages = $maxPages;
        $pagination->maxPageLinks = $maxPageLinks;

        $pages = $pagination->getPages();
        $this->assertCount($maxPageLinks + 1, $pages);

        for ($i = 0; $i < $maxPageLinks; $i++) {
            $page = $pages[$i];

            $this->assertEquals($expected['pages'][$i], $page['display']);
            $this->assertEquals($expected['disabled'][$i], $page['disabled']);
            $this->assertEquals($expected['active'] === $i, $page['active']);
        }
    }
}