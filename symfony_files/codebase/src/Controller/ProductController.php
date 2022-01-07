<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProductController
 * @package App\Controller
 * @Route("/api", name="Product_api")
 */
class ProductController extends AbstractController
{
    /**
     * @param ProductRepository $ProductRepository
     * @return JsonResponse
     * @Route("/products", name="Products", methods={"GET"})
     */
    public function getProducts(ProductRepository $ProductRepository)
    {
        $data = $ProductRepository->findAll();
        return $this->response($data);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param ProductRepository $ProductRepository
     * @return JsonResponse
     * @throws \Exception
     * @Route("/product/new", name="Products_add", methods={"POST"})
     */
    public function addProduct(Request $request, EntityManagerInterface $entityManager, ProductRepository $ProductRepository)
    {

        try {
            $request = $this->transformJsonBody($request);

            if (!$request || !$request->get('name') || !$request->request->get('price')) {
                throw new \Exception();
            }

            $Product = new Product();
            $Product->setName($request->get('name'));
            $Product->setPrice($request->get('price'));
            $entityManager->persist($Product);
            $entityManager->flush();

            $data = [
                'status' => 200,
                'success' => "Product added successfully",
            ];
            return $this->response($data);
        } catch (\Exception $e) {
            $data = [
                'status' => 422,
                'errors' => $request,
            ];
            return $this->response($data, 422);
        }
    }


    /**
     * @param ProductRepository $ProductRepository
     * @param $id
     * @return JsonResponse
     * @Route("/products/{id}", name="Products_get", methods={"GET"})
     */
    public function getProduct(ProductRepository $ProductRepository, $id)
    {
        $Product = $ProductRepository->find($id);

        if (!$Product) {
            $data = [
                'status' => 404,
                'errors' => "Product not found",
            ];
            return $this->response($data, 404);
        }
        return $this->response($Product);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param ProductRepository $ProductRepository
     * @param $id
     * @return JsonResponse
     * @Route("/products/{id}", name="Products_put", methods={"PUT"})
     */
    public function updateProduct(Request $request, EntityManagerInterface $entityManager, ProductRepository $ProductRepository, $id)
    {

        try {
            $Product = $ProductRepository->find($id);

            if (!$Product) {
                $data = [
                    'status' => 404,
                    'errors' => "Product not found",
                ];
                return $this->response($data, 404);
            }

            $request = $this->transformJsonBody($request);

            if (!$request || !$request->get('name') || !$request->request->get('description')) {
                throw new \Exception();
            }

            $Product->setName($request->get('name'));
            $Product->setDescription($request->get('description'));
            $entityManager->flush();

            $data = [
                'status' => 200,
                'errors' => "Product updated successfully",
            ];
            return $this->response($data);
        } catch (\Exception $e) {
            $data = [
                'status' => 422,
                'errors' => "Data no valid",
            ];
            return $this->response($data, 422);
        }
    }


    /**
     * @param ProductRepository $ProductRepository
     * @param $id
     * @return JsonResponse
     * @Route("/products/{id}", name="Products_delete", methods={"DELETE"})
     */
    public function deleteProduct(EntityManagerInterface $entityManager, ProductRepository $ProductRepository, $id)
    {
        $Product = $ProductRepository->find($id);

        if (!$Product) {
            $data = [
                'status' => 404,
                'errors' => "Product not found",
            ];
            return $this->response($data, 404);
        }

        $entityManager->remove($Product);
        $entityManager->flush();
        $data = [
            'status' => 200,
            'errors' => "Product deleted successfully",
        ];
        return $this->response($data);
    }





    /**
     * Returns a JSON response
     *
     * @param array $data
     * @param $status
     * @param array $headers
     * @return JsonResponse
     */
    public function response($data, $status = 200, $headers = [])
    {
        return new JsonResponse($data, $status, $headers);
    }

    protected function transformJsonBody(\Symfony\Component\HttpFoundation\Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if ($data === null) {
            return $request;
        }

        $request->request->replace($data);

        return $request;
    }
}
