<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Cart;
use AppBundle\Entity\CartProduct;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // get cart
        $cartRepo = $this->getDoctrine()->getRepository('AppBundle:Cart');
        $cart = $cartRepo->findOneBy(['user' => $this->getUser()]);

        $productRepo = $this->getDoctrine()->getRepository('AppBundle:Product');
        if(!empty($cart)) {
            $cartProductRepo = $this->getDoctrine()->getRepository('AppBundle:CartProduct');
            $inCartProducts = $cartProductRepo->findBy(['cart' => $cart]);
        }
        else {
            $inCartProducts = [];
        }

        $interestedIn = $productRepo->findUserInterestedIn($inCartProducts);

        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'interestedIn' => $interestedIn,
            'cart' => $cart
        ]);
    }

    /**
     * @Route("/cart/add", name="addToCart")
     */
    public function addToCart(Request $request){
        $em = $this->getDoctrine()->getManager();

        $productRepo = $this->getDoctrine()->getRepository('AppBundle:Product');
        $cartRepo = $this->getDoctrine()->getRepository('AppBundle:Cart');
        $cartProductRepo = $this->getDoctrine()->getRepository('AppBundle:CartProduct');
        $currentUser = $this->getUser();

        $cart = $cartRepo->findOneBy(['user' => $currentUser]);
        // if empty create the cart
        if(empty($cart)){
            $cart = new Cart();
            $cart->setUser($currentUser);
            $em->persist($cart);
        }
        $productID = $request->query->get('product_id');
        $qty = $request->query->get('qty');
        // default value for quantity
        $qty = (is_int($qty) && $qty >= 0 ? $qty : 0);
        // find the product
        if(!empty($productID)) {
            $product = $productRepo->find($productID);
        }

        if(!empty($product)){
            $cartProduct = $cartProductRepo->findOneBy(['cart' => $cart, 'product' => $product]);
            if(empty($cartProduct)){
                $cartProduct = new CartProduct();
                $cartProduct->setCart($cart);
                $cartProduct->setProduct($product);
                $cartProduct->setQuantity(0);
            }
            $cartProduct->setQuantity($cartProduct->getQuantity() + $qty);
            $em->persist($cartProduct);
        }

        $em->flush();

        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/cart/remove", name="removeFromCart")
     */
    public function removeFromCart(Request $request){
        $em = $this->getDoctrine()->getManager();

        $productRepo = $this->getDoctrine()->getRepository('AppBundle:Product');
        $cartRepo = $this->getDoctrine()->getRepository('AppBundle:Cart');
        $cartProductRepo = $this->getDoctrine()->getRepository('AppBundle:CartProduct');
        $currentUser = $this->getUser();

        $cart = $cartRepo->findOneBy(['user' => $currentUser]);
        // if empty create the cart
        if(!empty($cart)){
            $productID = $request->query->get('product_id');
            // find the product
            if(!empty($productID)) {
                $product = $productRepo->find($productID);
                if(!empty($product)){
                    $cartProduct = $cartProductRepo->findOneBy(['cart' => $cart, 'product' => $product]);
                    $em->remove($cartProduct);
                    $em->flush();
                }
            }
        }
        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/wishlist", name="wishlist")
     */
    public function whishlistAction(){
        $productRepo = $this->getDoctrine()->getRepository('AppBundle:Product');
        $lproducts = $productRepo->findAll();

        return $this->render('default/wishlist.html.twig', [
            'lproducts' => $lproducts
        ]);
    }

    /**
     * @Route("/products/all", name="allProducts")
     */
    public function allProductstAction(){
        $productRepo = $this->getDoctrine()->getRepository('AppBundle:Product');
        $lproducts = $productRepo->findAll();

        return $this->render('default/all.html.twig', [
            'lproducts' => $lproducts
        ]);
    }
}
