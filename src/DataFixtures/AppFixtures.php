<?php

namespace App\DataFixtures;

use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private $books = [
        [
            "isbn" => "9781593275846",
            "title" => "Eloquent JavaScript, Second Edition",
            "author" => "Marijn Haverbeke",
            "published" => "2014",
            "description" => "JavaScript lies at the heart of almost every modern web application, from social apps to the newest browser-based games. Though simple for beginners to pick up and play with, JavaScript is a flexible, complex language that you can use to build full-scale applications.",
            "img" => "http://eloquentjavascript.net/",
            "price" => 33.45,
            "status" => false
        ],
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
        [
            "isbn" => "9781449365035",
            "title" => "Speaking JavaScript",
            "author" => "Axel Rauschmayer",
            "published" => "2014",
            "description" => "Like it or not, JavaScript is everywhere these days-from browser to server to mobile-and now you, too, need to learn the language or dive deeper than you have. This concise book guides you into and through JavaScript, written by a veteran programmer who once found himself in the same position.",
            "img" => "http://speakingjs.com/",
            "price" => 4.45,
            "status" => true
        ],
    ];

    public function load(ObjectManager $manager)
    {
        foreach ($this->books as $bookData) {
            $book = new Book();
            $book->setTitle($bookData['title']);
            $book->setAuthor($bookData['author']);
            $book->setPublished($bookData['published']);
            $book->setIsbn($bookData['isbn']);
            $book->setPrice($bookData['price']);
            $book->setDescription($bookData['description']);
            $book->setImg($bookData['img']);
            $book->setStatus($bookData['status']);
            $manager->persist($book);
        }

        $manager->flush();
    }
}
