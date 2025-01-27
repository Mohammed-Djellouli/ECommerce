<?php
namespace App\Classe;

use Symfony\Component\HttpFoundation\RequestStack;

class Cart
{

    public function __construct(private RequestStack $requestStack)
    {

    }

    /**
     * @param $product
     * @return void
     * function permettant l'ajout d'un produit au panier
     */

    public function add($product)
    {
        //Appeler la session de symfony
        $cart = $this->requestStack->getSession()->get('cart');
        //Ajouter une quantity +1 à mon produit
        if(isset($cart[$product->getId()])){
            $cart[$product->getId()] =[
                'object' => $product,
                'qty' => $cart[$product->getId()]['qty'] + 1
            ];
        }else{
            //Ajouter une quantity 1 à mon produit
            $cart[$product->getId()] =[
                'object' => $product,
                'qty' => 1
            ];
        }

        //Créer ma session Carte
        $this->requestStack->getSession()->set('cart', $cart);


    }

    public function getCart(){
        return $this->requestStack->getSession()->get('cart');
    }


    /**
     * @return mixed
     * function permettant de supprimer completement le panier
     */
    public function remove(){
        return $this->requestStack->getSession()->remove('cart');
    }


    /**
     * @param $id
     * @return void
     * function permettant la suppression d'une quantity d'un produit au panier
     */
    public function decrease($id){
        $cart = $this->requestStack->getSession()->get('cart');
        if($cart[$id]['qty'] > 1){
            $cart[$id]['qty']--;
        }else{
            unset($cart[$id]);
        }
        $this->requestStack->getSession()->set('cart', $cart);
    }

    /**
     * @return int|mixed
     * function permettant de savoir la quantity totale de produits dans le panier
     */
    public function fullQuantity()
    {
        $cart = $this->requestStack->getSession()->get('cart');
        $quantity = 0;
        if(!isset($cart)){
            return $quantity;
        }
        foreach($cart as $product){
            $quantity += $product['qty'];
        }

        return $quantity;
    }


    /**
     * @return float|int
     * function permettant de savoir le prix total de produit dans la panier
     */

    public function getTotalWt(){
        $cart = $this->requestStack->getSession()->get('cart');
        $price = 0;
        if(!isset($cart)){
            return $price;
        }
        foreach($cart as $product){
            $price += $product['object']->getPriceWt() * $product['qty'];
        }

        return $price;
    }
}