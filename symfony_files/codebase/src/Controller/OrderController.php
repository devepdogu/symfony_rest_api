<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Product;
use App\Entity\User;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use App\Repository\ProductRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class OrderController
 * @package App\Controller
 * @Route("/api", name="Order_api")
 */
class OrderController extends AbstractController
{
    /**
     * @param OrderRepository $OrderRepository
     * @return JsonResponse
     * @Route("/orders", name="Orders", methods={"GET"})
     */
    public function getOrders(OrderRepository $OrderRepository)
    {

        $Order = $OrderRepository->findAllJoin();
        if (!$Order) {
            $data = [
                'status' => 404,
                'errors' => "Order not found",
            ];
            return $this->response($data, 404);
        }
        return $this->response($Order);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param OrderRepository $OrderRepository
     * @return JsonResponse
     * @throws \Exception
     * @Route("/order/new", name="Orders_add", methods={"POST"})
     */
    public function addOrder(Request $request, EntityManagerInterface $entityManager, OrderRepository $OrderRepository, ProductRepository $ProductRepository, UserRepository $UserRepository)
    {

        try {
            $request = $this->transformJsonBody($request);

            if (!($request->get('productid')) || !($request->get('userid')) || !($request->get('quantity')) ||  !($request->get('address'))) {
                throw new \Exception();
            }

            $product = $ProductRepository->find(array('id' => $request->get('productid')));
            $user = $UserRepository->find(array('id' => $request->get('userid')));
            if ($product == null || $user == null) {
                $data = [
                    'status' => 404,
                    'success' => "Product or user not found!",
                ];
                return $this->response($data);
            }
            $Order = new Order();

            $Order->setUser($user);
            $Order->setProduct($product);
            $Order->setQuantity($request->get('quantity'));
            $Order->setAddress($request->get('address'));
            $Order->setShippingDate($request->get('shippingdate'));

            $entityManager->persist($Order);

            $entityManager->flush();

            $data = [
                'status' => 200,
                'success' => "Order added successfully",

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
     * @param OrderRepository $OrderRepository
     * @param $id
     * @return JsonResponse
     * @Route("/orders/{id}", name="Orders_get", methods={"GET"})
     */
    public function getOrder(OrderRepository $OrderRepository, $id)
    {
        $Order = $OrderRepository->findAllJoinWithId($id);
        if (!$Order) {
            $data = [
                'status' => 404,
                'errors' => "Order not found",
            ];
            return $this->response($data, 404);
        }
        return $this->response($Order);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param OrderRepository $OrderRepository
     * @param $id
     * @return JsonResponse
     * @Route("/orders/{id}", name="Orders_put", methods={"PUT"})
     */
    public function updateOrder(Request $request, EntityManagerInterface $entityManager, OrderRepository $OrderRepository, ProductRepository $ProductRepository, UserRepository $UserRepository, $id)
    {

        try {
            $Order = $OrderRepository->find($id);

            if (!$Order) {
                $data = [
                    'status' => 404,
                    'errors' => "Order not found",
                ];
                return $this->response($data, 404);
            }

            $request = $this->transformJsonBody($request);

            if (!$request  || !$request->get('quantity') || !$request->get('address')) {
                throw new \Exception();
            }

            $product = $ProductRepository->find(array('id' => $request->get('productid')));


            $user = $UserRepository->find(array('id' => $request->get('userid')));
            if ($product == null || $user == null) {
                $data = [
                    'status' => 404,
                    'success' => "Product or user not found!",
                ];
                return $this->response($data);
            }

            //shippingDate verilmiş ise
            if ($Order->getShippingDate() > 0) {
                $data = [
                    'status' => 404,
                    'success' => "Shipping date entered!",
                ];
                return $this->response($data);
            }

            // $Order->setProduct($product);
            // $Order->setUser($user);
            $Order->setQuantity($request->get('quantity'));
            $Order->setAddress($request->get('address'));
            // $Order->setShippingDate($request->get('shippingdate'));
            $entityManager->flush();

            $data = [
                'status' => 200,
                'errors' => "Order updated successfully"
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
     * @param OrderRepository $OrderRepository
     * @param $id
     * @return JsonResponse
     * @Route("/orders/{id}", name="Orders_delete", methods={"DELETE"})
     */
    public function deleteOrder(EntityManagerInterface $entityManager, OrderRepository $OrderRepository, $id)
    {
        $Order = $OrderRepository->find($id);

        if (!$Order) {
            $data = [
                'status' => 404,
                'errors' => "Order not found",
            ];
            return $this->response($data, 404);
        }

        $entityManager->remove($Order);
        $entityManager->flush();
        $data = [
            'status' => 200,
            'errors' => "Order deleted successfully",
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
