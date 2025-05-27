<?php
namespace SoampliApps\Pagination;

class PdoTraitTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->_pagination = new \SoampliApps\Pagination\Pagination();
    }

    public function testPreparingWithPaginationSetsPaginationAndCallsPrepare()
    {
        $sql = sha1(time());
        $options = ['prop' => sha1(time())];
        $mock = $this->getMockBuilder('\SoampliApps\Pagination\PdoTrait')->setMethods(['prepare'])->getMockForTrait();
        $mock->expects($this->once())->method('prepare')->with($this->equalTo($sql), $this->equalTo($options));

        $mock->prepareWithPagination($sql, $this->_pagination, $options);
        $this->assertSame($this->_pagination, $mock->getPagination());
    }
}
