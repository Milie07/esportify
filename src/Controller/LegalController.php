<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LegalController extends AbstractController
{
  public function cgu():Response
  {
    return $this->render('legal/cgu.html.twig');
  }
  public function privacy(): Response
  {
      return $this->render('legal/privacy.html.twig');
  }
  public function legalMentions(): Response
  {
      return $this->render('legal/mentions.html.twig');
  }
}