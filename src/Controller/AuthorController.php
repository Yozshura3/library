<?php


namespace App\Controller;


use App\Entity\Author;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @Route("/author/insert", name="author_insert")
     */

    public function insertAuthor(EntityManagerInterface $entityManager , Request $request)
    {

        $name = $request->query->get('name');
        $fname = $request->query->get('firstName');
        $bdate = $request ->query->get('birthDate');
        $ddate = $request->query->get('deathDate');
        $bio = $request->query->get('biography');

        // insérer dans la table book un nouveau livre
        $author = new Author();
        $author->setName($name);
        $author->setFirstName($fname);
        $author->setBirthDate(new \DateTime($bdate));
        $author->setDeathDate($ddate);
        $author->setBiography($bio);

        $entityManager->persist($author);
        $entityManager->flush();

        return $this->render('insert.html.twig',[
        ]);
    }

    /**
     * @Route("/author/delete", name="author_delete")
     */

    public function deleteAuthor(AuthorRepository $authorRepository, EntityManagerInterface $entityManager)
    {
        // Je récupère un enregistrement book en BDD grâce au repository de book
        $author = $authorRepository->find(1);

        // J'utilise l'entity manager avec la méthode remove pour enregistrer la suppression du book dans l'unité de travail
        $entityManager->remove($author);

        // Je valide la suppression en bdd avec la méthode flush
        $entityManager->flush();

        return $this->redirectToRoute('author_delete');
    }

}
