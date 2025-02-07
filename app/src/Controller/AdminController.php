<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{
  #[Route('/api/admin', name: 'admin')]
  public function index(): Response
  {
    return new Response('Welcome to the admin page!');
  }
}
