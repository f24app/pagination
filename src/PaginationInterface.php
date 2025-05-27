<?php
namespace SoampliApps\Pagination;

interface PaginationInterface
{
    public function getTotalNumberOfPages();

    public function isFirstPage();

    public function isLastPage();

    public function hasNext();

    public function hasPrevious();

    public function setTotalResults($total_results);

    public function getCurrentPageNumber();

    public function getMaxResultsPerPage();

    public function setMaxResultsPerPage($max_results_per_page);

    public function setCurrentPageNumber($current_page_number);
}
