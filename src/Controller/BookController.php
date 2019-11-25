<?php


namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

        dump($books); die;
    }



}