<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Order;
use App\Entity\OrderProducts;
use App\Form\OrderType;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\services\Cart;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class OrderController extends AbstractController
{
    #[Route('/order', name: 'app_order')]
    public function index(Cart $cart,EntityManagerInterface $em ,Request $request,SessionInterface $session,ProductRepository $productRepository): Response
    {
        $data = $cart->getCart($session);

        $order = new Order();
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if($order->isPayOnDelivery()){
                if(!empty($data['total'])){
                    $order->setTotalPrice($data['total']);
                    $order->setCreatedAt(new \DateTimeImmutable());
                    $em->persist($order);
                    $em->flush();
                    foreach($data['cart'] as $value){
                        $orderProduct = new OrderProducts();
                        $orderProduct->setOrder($order);
                        $orderProduct->setProduct($value['product']);
                        $orderProduct->setQte($value['quantity']);
                        $em->persist($orderProduct);
                        $em->flush();
                    }
                }
                $session->set('cart',[]);
                return $this->redirectToRoute('order_message');
            }
        }
        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'total' => $data['total']
        ]);
    }
    #[Route('/editor/order', name: 'app_orders_show')]
    public function getAllOrders(OrderRepository $orderRepository,Request $request,PaginatorInterface $paginator):Response
    {
        $data = $orderRepository->findBy([],['id' => 'DESC']);
        $order =$paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            1
        );
        return $this->render('order/order.html.twig',[
            'orders' => $order
        ]);
    }
    #[Route("/order-message", name: 'order_message')]
    public function orderMessage():Response{
        return $this->render('order/ordermessage.html.twig');
    }
#[Route('/city/{id}/shipping/cost', name: 'app_city_shipping_cost')]
public function cityShippingCost(City $city): Response{
 $cityShippingPrice = $city->getShippingCost();
 return new Response(json_encode(['status'=>200,'message'=>'on','content'=>$cityShippingPrice]));
}

}
