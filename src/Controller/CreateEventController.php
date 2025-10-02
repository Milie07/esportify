<?php
namespace App\Controller; 
use App\Entity\Tournament;
use App\Service\InputSanitizer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response, File\UploadedFile};
use Symfony\Component\String\Slugger\SluggerInterface;

final class CreateEventController extends AbstractController
{
    public function create(
        Request $req,
        InputSanitizer $san,
        SluggerInterface $slugger,
        EntityManagerInterface $em
    ): Response {
        // ...
        return $this->render('event/create.html.twig');
    }
}