<?php

namespace App\Controller;

use App\Service\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SecurityController extends AbstractController
{
  private UserManager $userManager;

  public function __construct(UserManager $userManager)
  {
    $this->userManager = $userManager;
  }

  #[Route('/api/login', name: 'app_login', methods: ['POST'])]
  public function login(): Response
  {
    return $this->json('Login');
  }

  #[Route('/api/register', name: 'app_register', methods: ['POST'])]
  public function register(Request $request): Response
  {
    $data = json_decode($request->getContent(), true);

    // dd($data);

    $roles = ['ROLE_USER'];

    $user = $this->userManager->createUser(
      $data['email'],
      $data['password'],
      $data['first_name'],
      $data['last_name'],
      $roles
    );

    // dd($user);

    return $this->json('User registered');
  }
}
