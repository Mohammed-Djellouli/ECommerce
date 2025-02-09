<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CartController extends AbstractController
{
    #[Route('/mon-panier/{motif}', name: 'app_cart',defaults: ['motif'=>null])]
    public function index(Cart $cart,$motif): Response
    {
        if($motif === "annulation"){
            $this->addFlash(
                'info',
                "Paiement annulé"
            );
        }
        return $this->render('cart/index.html.twig',[
            'cart' => $cart->getCart(),
            'totalWt' => $cart->getTotalWt(),
        ]);
    }

    #[Route('/cart/add/{id}', name: 'app_cart_add')]
    public function add($id,Cart $cart,ProductRepository $productRepository,Request $request): Response
    {

        $product = $productRepository->findOneById($id);
        $cart->add($product);

        $this->addFlash(
            'success',
            'Produit ajouté au panier'
        );
        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/cart/remove', name: 'app_cart_remove')]
    public function remove(Cart $cart): Response
    {
        $cart->remove();

        return $this->redirectToRoute('app_home');
    }


    #[Route('/cart/decrease/{id}', name: 'app_cart_decrease')]
    public function decrease($id,Cart $cart): Response
    {

        $cart->decrease($id);

        $this->addFlash(
            'success',
            'Produit supprimer au panier'
        );
        return $this->redirectToRoute('app_cart');
    }

}
