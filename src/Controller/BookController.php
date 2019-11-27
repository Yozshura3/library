<?php


namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
class BookController extends AbstractController
{
    /**
     * @Route("/book_db_list", name="book_db_list")
     */
    public function bookList(BookRepository $bookRepository)
    {
        $books = $bookRepository->findAll();

        return $this->render('booklist.html.twig', [
            'books'=>$books
        ]);
    }

    /**
     * @Route("/livre/{id}", name="livre")
     */

    public function livreArticle($id, BookRepository $bookRepository){

        $book = $bookRepository->find($id);

        return $this->render('livre.html.twig', [
            'book'=>$book
        ]);
    }

    /**
     * @Route("/books_by_genre", name="books_by_genre")
     */
    public function getBooksByGenre(BookRepository $bookRepository)
    {
        $books = $bookRepository->getByGenre();

        // Appelle le bookRepository (en le passant en paramètre de la méthode
        // Appelle la méthode qu'on a créée dans le bookRepository ("getByGenre()")
        // Cette méthode est censée nous retourner tous les livres en fonction d'un genre
        // Elle va donc executer une requete SELECT en base de données.

    }

    /**
     * @Route("/book/insert", name="book_insert")
     */

    public function insertBook(EntityManagerInterface $entityManager)
    {
        // insérer dans la table book un nouveau livre
        $book = new Book();
        $book->setTitle('La clé du succès');
        $book->setStyle('escroquerie');
        $book->setInStock(true);
        $book->setNbPages(223);

        $entityManager->persist($book);
        $entityManager->flush();

        var_dump('livre enregistré'); die;
    }

    /**
     * @Route("/book/delete", name="book_delete")
     */
    public function deleteBook(BookRepository $bookRepository, EntityManagerInterface $entityManager)
    {
        // Je récupère un enregistrement book en BDD grâce au repository de book
        $book = $bookRepository->find(1);

        // J'utilise l'entity manager avec la méthode remove pour enregistrer la suppression du book dans l'unité de travail
        $entityManager->remove($book);

        // Je valide la suppression en bdd avec la méthode flush
        $entityManager->flush();
    }

    /**
     * @Route("/book/update/{id}", name="book_update")
     */
    public function updateBook(BookRepository $bookRepository, EntityManagerInterface $entityManager, $id)
    {
        // j'utilise le Repository de l'entité Book pour récupérer un livre
        // en fonction de son id
        $book = $bookRepository->find($id);

        // Je donne un nouveau titre à mon entité Book
        $book->setTitle('Luc et Natasha dans le chalet');
        $book->setStyle('Romantique');

        // je re-enregistre mon livre en BDD avec l'entité manager
        $entityManager->persist($book);
        $entityManager->flush();

        return $this->redirectToRoute('book_db_list');
    }

    /**
     * @Route("/book/insert_form", name="book_insert_form")
     */
    public function insertBookForm(Request $request, EntityManagerInterface $entityManager)
    {

        // J'utilise le gabarit de formulaire pour créer mon formulaire
        // J'envoie mon formulaire à un fichier twig
        // et je l'affiche

        // Je créé un nouveau Book,
        // en créant une nouvelle instance de l'entité Book
        $book = new Book();

        // J'utilise la méthode createForm pour créer le gabarit de formulaire
        // pour le Book: BookType (que j'ai généré en ligne de commandes)
        // et je lui associe mon entité Book vide

        $bookForm = $this->createForm(BookType::class, $book);

        // si je suis sur une méthode POST
        //donc qu'un formulaire a été envoyé
        if($request->isMethod('POST')) {

            // Je récupère les données de la requête (POST)
            // et je les associes à mon formulaire
            $bookForm->handleRequest($request);

            // Si les données de mon formulaire sont valides
            // (que les types rentrés dans les inputs sont bons,
            // que tous les champs obligatoires sont remplis etc)
            if ($bookForm->isValid()) {
                // J'enregistre en BDD ma variable $book
                // qui n'est plus vide, car elle a été remplie
                // avec les données du formulaire
                $entityManager->persist($book);
                $entityManager->flush();
            }
        }


        // À partir de mon gabarit, je crée la vue de mon formulaire
        $bookFormView = $bookForm->createView();

        // Je retourne un fichier twig, et je lui envoie ma variable qui contient
        // mon formulaire
        return $this->render('book/insert_form.html.twig', [
            'bookFormView' => $bookFormView
        ]);
    }

}