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
     *          name="criteria",
     *          @OA\Schema(
     *              type="object",
     *              description="associative array - find book by values in coresponding keys",
     *              example="[`title`: `Trip to america`, `published` => `2014`]"
     *          ),
     *      ),
     *     @OA\Response(
     *          response="200",
     *          description="json data with books",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(type="string", property="status"),
     *                  @OA\Property(type="array",
     *                      property="data",
     *                      description="Books objects in array",
     *                      @OA\Items(
     *                          type="object",
     *                          ref="#/components/schemas/Book"
     *                      )
     *                  )
     *              ),
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

        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent($serializer->serialize([
            'status' => 'ok',
            'data'=> $books
        ], 'json'));

        return $response;
    }
    /**
     * @OA\Post(
     *     path="/api/all",
     *     summary="list all books",
     *     @OA\Response(
     *          response="200",
     *          description="json data with books",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(type="string", property="status"),
     *                  @OA\Property(type="array",
     *                      property="data",
     *                      description="Books objects in array",
     *                      @OA\Items(
     *                          type="object",
     *                          ref="#/components/schemas/Book"
     *                      )
     *                  )
     *              ),
     *          )
     *     )
     * )
     * @Route("/api/all", name="api_all")
     */
    public function allBooks(SerializerInterface $serializer): Response
    {
        $books = $this->getDoctrine()
            ->getRepository(Book::class)
            ->findAll();

        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent($serializer->serialize([
            'status' => 'ok',
            'data'=> $books
        ], 'json'));

        return $response;
    }
    /**
     * @OA\Post(
     *     path="/api/add",
     *     summary="add book",
     *     @OA\Response(
     *          response=200,
     *          description="json data with books",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(type="string", property="status", default="ok"),
     *              ),
     *          )
     *     ),
     *     @OA\Response(
     *          response=400,
     *          description="book params not provided corectly",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(type="string", property="status", default="error"),
     *                  @OA\Property(type="object", property="errors"),
     *              ),
     *          )
     *     )
     * )
     * @Route("/api/add", name="api_add", methods={"POST"})
     */
    public function addBook(Request $request, SerializerInterface $serializer): Response
    {
        $data = $request->request->all();
        $form = $this->createForm(BookType::class,  new Book());

        $form->submit($data);

        if (false === $form->isValid()) {
            $errors = [];
            foreach ($form->all() as $child) {
                if (!$child->isValid()) {
                    $errors[$child->getName()] = $form[$child->getName()]->getErrors()->current()->getMessage();
                }
            }
            return new Response($serializer->serialize([
                'status' => 'error',
                'errors' => $errors,
            ], 'json'), JsonResponse::HTTP_BAD_REQUEST);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($form->getData());
        $em->flush();


        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent($serializer->serialize([
            'status' => 'ok',
        ], 'json'));

        return $response;
    }
}
