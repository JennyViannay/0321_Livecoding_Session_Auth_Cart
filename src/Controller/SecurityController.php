<?php

/**
 * Created by PhpStorm.
 * User: aurelwcs
 * Date: 08/04/19
 * Time: 18:40
 */

namespace App\Controller;

use App\Model\UserManager;

class SecurityController extends AbstractController
{
    /**
     * Display home page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function register()
    {
        $userManager = new UserManager();

        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            if (!empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['password2'])) {
                $user = $userManager->searchByUsername($_POST['username']);
                if (!$user) {
                    if ($_POST['password'] === $_POST['password2']) {
                        $user = [
                            'username' => $_POST['username'],
                            'password' => md5($_POST['password']),
                        ];
                        $id = $userManager->insert($user);
                        $user = $userManager->selectOneById($id);
                        $_SESSION['user'] = $user;
                        header('Location: /');
                    }
                }
            }
        }
        return $this->twig->render('Security/register.html.twig');
    }

    public function login()
    {
        $userManager = new UserManager();
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            if (!empty($_POST['username']) && !empty($_POST['password'])) {
                $user = $userManager->searchByUsername($_POST['username']);
                if ($user) {
                    if (md5($_POST['password']) === $user['password']) {
                        $_SESSION['user'] = $user;
                        header('Location: /');
                    }
                } else {
                    $errors[] = "user not found !";
                }
            } else {
                $errors[] = "Tous les champs sont requis !";
            }
        }
        return $this->twig->render('Security/login.html.twig', [
            'errors' => $errors
        ]);
    }

    public function logout()
    {
        session_destroy();
        header('Location: /');
    }
}
