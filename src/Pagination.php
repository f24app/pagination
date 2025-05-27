<?php
namespace SoampliApps\Pagination;

class Pagination implements PaginationInterface
{
    public $totalResults = 0;
    public $currentPageNumber = 1;
    public $maxResultsPerPage = 3;

    public function getTotalNumberOfPages()
    {
        return ceil($this->totalResults / $this->maxResultsPerPage);
    }

    public function isFirstPage()
    {
        return (1 == $this->currentPageNumber);
    }

    public function isLastPage()
    {
        return ($this->getTotalNumberOfPages() == $this->currentPageNumber);
    }

    public function hasNext()
    {
        return (!$this->isLastPage());
    }

    public function hasPrevious()
    {
        return (!$this->isFirstPage());
    }

    public function setTotalResults($total_results)
    {
        $this->totalResults = $total_results;
    }

    public function getCurrentPageNumber()
    {
        return $this->currentPageNumber;
    }

    public function getMaxResultsPerPage()
    {
        return $this->maxResultsPerPage;
    }

    public function setMaxResultsPerPage($max_results_per_page)
    {
        $this->maxResultsPerPage = $max_results_per_page;
    }

    public function setCurrentPageNumber($current_page_number)
    {
        $this->currentPageNumber = $current_page_number;
    }
}
