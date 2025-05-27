<?php
namespace SoampliApps\Pagination;

class PaginatedTraitTest extends \PHPUnit_Framework_TestCase
{
    use PaginatedTrait;

    public function setUp()
    {
        $this->_pagination = new \SoampliApps\Pagination\Pagination();
    }

    public function testSettingPagination()
    {
        $this->assertNull($this->pagination);
        $this->setPagination($this->_pagination);
        $this->assertSame($this->_pagination, $this->pagination);
    }

    public function testGettingPagination()
    {
        $this->assertNull($this->getPagination());
        $this->pagination = $this->_pagination;
        $this->assertSame($this->_pagination, $this->getPagination());
    }

    public function testIsPaginatedShouldReturnBooleanBasedOnPaginationPropertyBeingNull()
    {
        $this->assertNull($this->pagination);
        $this->assertFalse($this->isPaginated());
        $this->pagination = $this->_pagination;
        $this->assertTrue($this->isPaginated());
    }
}
