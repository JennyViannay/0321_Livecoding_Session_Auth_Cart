<?php

/**
 * Created by PhpStorm.
 * User: aurelwcs
 * Date: 08/04/19
 * Time: 18:40
 */

namespace App\Controller;

use App\Model\ArticleManager;

class HomeController extends AbstractController
{
    /**
     * Display home page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        $artManager = new ArticleManager();
        return $this->twig->render('Home/index.html.twig', [
            'articles' => $artManager->selectAll()
        ]);
    }

    public function cart()
    {
        return $this->twig->render('Home/cart.html.twig', [
            'cart' => $this->cartInfos(),
            'totalCart' => $this->getTotalCart()
        ]);
    }

    public function addToCart(int $idArticle)
    {
        if (!empty($_SESSION['cart'][$idArticle])) {
            $_SESSION['cart'][$idArticle]++;
        } else {
            $_SESSION['cart'][$idArticle] = 1; 
        }
        header('Location: /');
    }

    public function deleteFromCart(int $idArticle)
    {
        $cart = $_SESSION['cart'];
        if (!empty($cart[$idArticle])) {
            unset($cart[$idArticle]);
        }
        $_SESSION['cart'] = $cart;
        header('Location: /home/cart');
    }

    public function cartInfos()
    {
        $artManager = new ArticleManager();
        if (isset($_SESSION['cart'])) {
            $cart = $_SESSION['cart'];
            $cartInfos = [];
            foreach ($cart as $id => $qty) {
                $article = $artManager->selectOneById($id);
                $article['qty'] = $qty;
                $cartInfos[] = $article;
            }
            return $cartInfos;
        }
        return false;
    }

    public function getTotalCart()
    {
        $total = 0;
        if ($this->cartInfos() != false) {
            foreach($this->cartInfos() as $article) {
                $total += $article['price'] * $article['qty'];
            }
        }
        return $total;
    }

}
