<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/get", name="api_get", methods={"POST"})
     */
    public function getBook(Request $request, SerializerInterface $serializer): JsonResponse
    {
        $books = $this->getDoctrine()
            ->getRepository(Book::class)
            ->findBy($request->request->all());

        $json = $serializer->serialize([
            'status' => 'ok',
            'data'=> $books
        ], 'json');


        return new JsonResponse($json);
    }
    /**
     * @Route("/api/all", name="api_all", methods={"POST"})
     */
    public function allBooks(Request $request, SerializerInterface $serializer): JsonResponse
    {
        $books = $this->getDoctrine()
            ->getRepository(Book::class)
            ->findAll();

        $json = $serializer->serialize([
            'status' => 'ok',
            'data'=> $books
        ], 'json');


        return new JsonResponse($json);
    }
    /**
     * @Route("/api/add", name="api_dd", methods={"POST"})
     */
    public function addBook(Request $request, SerializerInterface $serializer): JsonResponse
    {
        $data = $request->request->all();
        $form = $this->createForm(BookType::class,  new Book());

        $form->submit($data);

        if (false === $form->isValid()) {
            return new JsonResponse($serializer->serialize([
                'status' => 'error',
                'errors' => $form->getErrors()
            ], 'json'), JsonResponse::HTTP_BAD_REQUEST);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($form->getData());
        $em->flush();


        return new JsonResponse($serializer->serialize([
            'status' => 'ok',
        ], 'json'));
    }
}
