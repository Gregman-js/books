<?php

namespace App\Tests\Controller;

use Generator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiTest extends WebTestCase
{
    public function testAllBooks(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/all');

        $response = json_decode(json_decode($client->getResponse()->getContent(), true), true);

        $this->assertResponseIsSuccessful();
        $this->assertEquals('ok', $response['status']);
        $this->assertIsArray($response['data']);

        $found = array_map(function ($book) {
            return $book['isbn'];
        }, $response['data']);

        $this->assertTrue($found === ['9781593275846', '9781449331818', '9781449365035']);
    }

    /**
     * @dataProvider provideAddData
     */
    public function testAddBookWithCase(array $book, bool $added): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/add', $book);

        $response = json_decode(json_decode($client->getResponse()->getContent(), true), true);

        if ($added) {
            $this->assertResponseIsSuccessful();
            $this->assertResponseStatusCodeSame(200);
            $this->assertEquals('ok', $response['status']);
        } else {
            $this->assertResponseStatusCodeSame(400);
            $this->assertEquals('error', $response['status']);
        }
    }

    /**
     * @dataProvider provideSearchData
     */
    public function testFindBookWithCase(array $findBy, array $correctIds): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/find', $findBy);

        $response = json_decode(json_decode($client->getResponse()->getContent(), true), true);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
        $this->assertEquals('ok', $response['status']);
        $this->assertIsArray($response['data']);

        $found = array_map(function ($book) {
            return $book['isbn'];
        }, $response['data']);

        $this->assertTrue($found === $correctIds);
    }

    /**
     * @return Generator
     */
    public function provideAddData(): Generator
    {
        yield 'proper book' => [
            [
                "isbn" => "9781449331818",
                "title" => "Learning JavaScript Design Patterns",
                "author" => "Addy Osmani",
                "published" => "2012",
                "description" => "With Learning JavaScript Design Patterns, you'll learn how to write beautiful, structured, and maintainable JavaScript by applying classical and modern design patterns to the language. If you want to keep your code efficient, more manageable, and up-to-date with the latest best practices, this book is for you.",
                "img" => "http://www.addyosmani.com/resources/essentialjsdesignpatterns/book/",
                "price" => 22.67,
                "status" => false
            ],
            true
        ];
        yield 'without optional params' => [
            [
                "isbn" => "9781449331818",
                "title" => "Learning JavaScript Design Patterns",
                "author" => "Addy Osmani",
                "published" => "2012",
                "price" => 22.67,
                "status" => false
            ],
            true
        ];
        yield 'not given title' => [
            [
                "isbn" => "9781449331818",
                "author" => "Addy Osmani",
                "published" => "2012",
                "description" => "With Learning JavaScript Design Patterns, you'll learn how to write beautiful, structured, and maintainable JavaScript by applying classical and modern design patterns to the language. If you want to keep your code efficient, more manageable, and up-to-date with the latest best practices, this book is for you.",
                "img" => "http://www.addyosmani.com/resources/essentialjsdesignpatterns/book/",
                "price" => 22.67,
                "status" => false
            ],
            false
        ];
        yield 'not given status' => [
            [
                "title" => "Learning JavaScript Design Patterns",
                "isbn" => "9781449331818",
                "author" => "Addy Osmani",
                "published" => "2012",
                "description" => "With Learning JavaScript Design Patterns, you'll learn how to write beautiful, structured, and maintainable JavaScript by applying classical and modern design patterns to the language. If you want to keep your code efficient, more manageable, and up-to-date with the latest best practices, this book is for you.",
                "img" => "http://www.addyosmani.com/resources/essentialjsdesignpatterns/book/",
                "price" => 22.67,
            ],
            false
        ];
    }

    /**
     * @return Generator
     */
    public function provideSearchData(): Generator
    {
        yield 'find by status (dostepny)' => [
            [
                'status' => true
            ],
            ['9781449365035']
        ];
        yield 'find by status (niedostepny)' => [
            [
                'status' => false
            ],
            ['9781593275846', '9781449331818']
        ];
        yield 'find by price' => [
            [
                'price' => 22.67
            ],
            ['9781449331818']
        ];
        yield 'find by author' => [
            [
                'author' => 'Marijn Haverbeke'
            ],
            ['9781593275846']
        ];
        yield 'find by published' => [
            [
                'published' => '2014'
            ],
            ['9781593275846', '9781449365035']
        ];
        yield 'find by published and author' => [
            [
                'published' => '2014',
                'author' => 'Axel Rauschmayer'
            ],
            ['9781449365035']
        ];
    }
}
