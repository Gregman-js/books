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
use OpenApi\Annotations as OA;

/**
 * @OA\Info(title="Book REST API", version="1")
 */
class ApiController extends AbstractController
{
    /**
     * @OA\Post(
     *     path="/api/find",
     *     summary="find book by parameter",
     *     @OA\Parameter(
     *          in="query",
     *          name="name",
     *          @OA\Schema(type="string"),
     *      ),
     *     @OA\Response(
     *          response="200",
     *          description="json data with books",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/Book"),
     *          )
     *     )
     * )
     * @Route("/api/find", name="api_find", methods={"POST"})
     */
    public function getBook(Request $request, SerializerInterface $serializer): Response
    {
        $books = $this->getDoctrine()
            ->getRepository(Book::class)
            ->findBy($request->request->all());

        $json = $serializer->serialize([
            'status' => 'ok',
            'data'=> $books
        ], 'json');


        return new Response($json);
    }
    /**
     * @OA\Post(
     *     path="/api/all",
     *     summary="list all books",
     *     @OA\Response(
     *          response="200",
     *          description="json data with all books",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/Book"),
     *          )
     *     )
     * )
     * @Route("/api/all", name="api_all")
     */
    public function allBooks(Request $request, SerializerInterface $serializer): Response
    {
        $books = $this->getDoctrine()
            ->getRepository(Book::class)
            ->findAll();

        $json = $serializer->serialize([
            'status' => 'ok',
            'data'=> $books
        ], 'json');

        return new Response($json);
    }
    /**
     * @OA\Post(
     *     path="/api/add",
     *     summary="add book",
     *     @OA\Response(
     *          response="200",
     *          description="OK",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *     )
     * )
     * @Route("/api/add", name="api_dd", methods={"POST"})
     */
    public function addBook(Request $request, SerializerInterface $serializer): Response
    {
        $data = $request->request->all();
        $form = $this->createForm(BookType::class,  new Book());

        $form->submit($data);

        if (false === $form->isValid()) {
            return new Response($serializer->serialize([
                'status' => 'error',
                'errors' => $form->getErrors()
            ], 'json'), JsonResponse::HTTP_BAD_REQUEST);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($form->getData());
        $em->flush();


        return new Response($serializer->serialize([
            'status' => 'ok',
        ], 'json'));
    }
}
