<?php
namespace SoampliApps\Pagination;

class PdoTest extends \PHPUnit_Framework_TestCase
{
    protected $pdo;

    public function setUp()
    {
        $this->pdo = new PdoForTesting();
    }

    public function testPdoUsesPdoTrait()
    {
        $this->assertContains('SoampliApps\Pagination\PdoTrait', class_uses('\SoampliApps\Pagination\Pdo'));
    }
}
