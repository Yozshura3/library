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

    /**
     * @Route("/bio_search", name="bio_search")
     */

    public function getWordInSearch(AuthorRepository $authorRepository)
    {
        $bio = $authorRepository->searchMotor();

        dump($bio); die;
    }

    public function getAuteursByName(AuthorRepository $authorRepository)
    {
        $word = 'k';

        // auteurRepository contient une instance de la classe 'AuteurRepository'
        // généralement on obtient une instance de classe (ou un objet en utilisant le mot clé "new"
        // ici, grace a symfony, on obtient l'instance de classe Repository en la passant simplement en parametre

        $auteurs = $auteurRepository->getAuthorByName($word);
        //Appelle le bookRepository(en le passant en parametre de la méthode)
        //appelle la méthode qu'on a créé dans le bookRepository ("getByGenre()")
        //Cette méthode est sensé nous retourner tous les livres en fonction d'un genre
        //elle va donc executer une requete SELECT en base de données
        return $this->render('selectby.html.twig', [
            'auteurs'=>$auteurs
        ]);
    }

}
