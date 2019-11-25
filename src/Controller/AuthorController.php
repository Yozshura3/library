<?php


namespace App\Controller;


use App\Entity\Author;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    /**
     * @Route("/author_db_list", name="author_db_list")
     */
    public function authorList(AuthorRepository $authorRepository)
    {
        $authors = $authorRepository->find(3);

        return $this->render('authorlist.html.twig', [
            'authors' => $authors
    ]);
    }
}