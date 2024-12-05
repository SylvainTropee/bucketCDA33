<?php

namespace App\Tests;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    //unitaire
    public function testSetCategoryName(): void
    {
        $category = new Category();
        $category->setName("category");

        $this->assertEquals("category", $category->getName(), "Le nom n'est pas bon !");
    }

    //intégration
    public function testMock()
    {

        $cat1 = new Category();
        $cat1->setName("Cat one");

        $cat2 = new Category();
        $cat2->setName("Cat two");


        $mock = $this->createMock(CategoryRepository::class);

        $mock
            ->expects($this->exactly(3))
            ->method('find')
            ->willReturnOnConsecutiveCalls($cat1, $cat2, null);

        $c1 = $mock->find(123);
        $c2 = $mock->find(123);
        $c3 = $mock->find(123);


        $this->assertEquals($cat1, $c1);
        $this->assertEquals($cat2, $c2);
        $this->assertEquals(null, $c3);


    }

}
