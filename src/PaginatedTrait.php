<?php
namespace SoampliApps\Pagination;

trait PaginatedTrait
{
    protected $pagination = null;

    public function setPagination(PaginationInterface $pagination)
    {
        $this->pagination = $pagination;
    }

    public function getPagination()
    {
        return $this->pagination;
    }

    public function isPaginated()
    {
        return (!is_null($this->pagination));
    }
}
