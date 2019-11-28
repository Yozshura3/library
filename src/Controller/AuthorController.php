<?php


namespace App\Controller;


use App\Entity\Author;
use App\Form\AuthorType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    /**
     * @Route("/authorlist", name="authorlist")
     */
    public function authorList(AuthorRepository $authorRepository)
    {
        $authors = $authorRepository->findAll();

        return $this->render('authorlist.html.twig', [
            'authors' => $authors
    ]);
    }

    /**
     * @Route("/admin/authorlist", name="admin_authorlist")
     */
    public function adminAuthorList(AuthorRepository $authorRepository)
    {
        $authors = $authorRepository->findAll();

        return $this->render('author/admin/admin_authorlist.html.twig', [
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

        $auteurs = $authorRepository->getAuthorByName($word);
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

        return $this->redirectToRoute('authorlist');
    }

    /**
     * @Route("/author/insert_form", name="author_insert_form")
     */
    public function insertBookForm(Request $request, EntityManagerInterface $entityManager)
    {

        // J'utilise le gabarit de formulaire pour créer mon formulaire
        // J'envoie mon formulaire à un fichier twig
        // et je l'affiche

        // Je créé un nouveau Book,
        // en créant une nouvelle instance de l'entité Book
        $author = new Author();

        // J'utilise la méthode createForm pour créer le gabarit de formulaire
        // pour le Book: BookType (que j'ai généré en ligne de commandes)
        // et je lui associe mon entité Book vide

        $authorForm = $this->createForm(AuthorType::class, $author);

        // si je suis sur une méthode POST
        //donc qu'un formulaire a été envoyé
        if ($request->isMethod('POST')) {

            // Je récupère les données de la requête (POST)
            // et je les associes à mon formulaire
            $authorForm->handleRequest($request);

            // Si les données de mon formulaire sont valides
            // (que les types rentrés dans les inputs sont bons,
            // que tous les champs obligatoires sont remplis etc)
            if ($authorForm->isValid()) {
                // J'enregistre en BDD ma variable $book
                // qui n'est plus vide, car elle a été remplie
                // avec les données du formulaire
                $entityManager->persist($author);
                $entityManager->flush();
            }
        }
        // À partir de mon gabarit, je crée la vue de mon formulaire
        $authorFormView = $authorForm->createView();

        // Je retourne un fichier twig, et je lui envoie ma variable qui contient
        // mon formulaire
        return $this->render('author/insert_form.html.twig', [
            'authorFormView' => $authorFormView
        ]);
    }


    /**
     * @Route("/author/update_form/{id}", name="author_update_form")
     */
    public function updateBookForm(AuthorRepository $authorRepository, Request $request, EntityManagerInterface $entityManager, $id )
    {
        // TODO: récupérer l'enregistrement / l'entité en BDD
        // TODO: créer le gabarit formulaire de Book
        // TODO: on récupère les d onnées envoyées par le formulaire
        // TODO: on écrase l'entité précédente dans la BDD

        $author = $authorRepository->find($id);

        $authorForm = $this->createForm(AuthorType::class, $author);

        if ($request->isMethod('POST'))
        {
            $authorForm->handleRequest($request);

            if ($authorForm->isValid()) {
                $entityManager->persist($author);
                $entityManager->flush();
            }

        }
        // À partir de mon gabarit, je crée la vue de mon formulaire
        $authorFormView = $authorForm->createView();

        // Je retourne un fichier twig, et je lui envoie ma variable qui contient
        // mon formulaire
        return $this->render('author/insert_form.html.twig', [
            'authorFormView' => $authorFormView
        ]);
    }
}
