<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;

final class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }
     #[Route('/insertbook', name: 'book_new')]
    public function insertBook(Request $request, EntityManagerInterface $em): Response
    {
        $book = new Book();
        $book->setPublished(true);

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()) {
            
             $author = $book->getAuthor();
            if ($author) {
                $currentNbBooks = $author->getNbBook();
                $author->setNbBook($currentNbBooks + 1);
                $em->persist($author); 
            }
           /* $author = $book->getAuthor();
            $author->setNbBooks($author->getNbBook() + 1);*/
            $em->persist($book);
            $em->flush();

            $this->addFlash('success', 'Livre ajouté avec succès !');
            return $this->redirectToRoute('book_list');
        }

        return $this->render('book/form.html.twig', [
                'form' => $form->createView(),
        ]);

    }
      #[Route('/books', name: 'book_list')]
    public function listBooks(BookRepository $bookRepository): Response
    {
        $books = $bookRepository->findAll();
        $nbPublished = $bookRepository->count(['published' => true]);
    $nbUnpublished = $bookRepository->count(['published' => false]);

        return $this->render('book/list.html.twig', [
            'books' => $books,
             'nbPublished' => $nbPublished,
             'nbUnpublished' => $nbUnpublished,
        ]);
    }
    #[Route('/book/edit/{id}', name: 'book_edit')]
public function editBook(Request $request, EntityManagerInterface $em, BookRepository $bookRepository, int $id): Response
{
    $book = $bookRepository->find($id);
    
    if (!$book) {
        throw $this->createNotFoundException('Livre non trouvé');
    }

    // Sauvegarder l'ancien auteur pour la décrémentation
    $oldAuthor = $book->getAuthor();
    $oldAuthorId = $oldAuthor ? $oldAuthor->getId() : null;

    $form = $this->createForm(BookType::class, $book);
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
        $newAuthor = $book->getAuthor();
        $newAuthorId = $newAuthor ? $newAuthor->getId() : null;

        // Gestion de l'incrémentation/décrémentation du nb_books
        if ($oldAuthorId !== $newAuthorId) {
            // Décrémenter l'ancien auteur
            if ($oldAuthor) {
                $currentNbBooks = $oldAuthor->getNbBook();
                $oldAuthor->setNbBook(max(0, $currentNbBooks - 1)); // Éviter les valeurs négatives
                $em->persist($oldAuthor);
            }
            
            // Incrémenter le nouvel auteur
            if ($newAuthor) {
                $currentNbBooks = $newAuthor->getNbBook();
                $newAuthor->setNbBook($currentNbBooks + 1);
                $em->persist($newAuthor);
            }
        }
        
        $em->flush();

        $this->addFlash('success', 'Livre modifié avec succès !');
        return $this->redirectToRoute('book_list');
    }

    return $this->render('book/edit.html.twig', [
        'form' => $form->createView(),
        'book' => $book,
    ]);
}
#[Route('/book/delete/{id}', name: 'book_delete')]
public function deleteBook(EntityManagerInterface $em, BookRepository $bookRepository, int $id): Response
{
    $book = $bookRepository->find($id);
    
    if (!$book) {
        throw $this->createNotFoundException('Livre non trouvé');
    }

    // Décrémenter le nb_books de l'auteur
    $author = $book->getAuthor();
    if ($author) {
        $currentNbBooks = $author->getNbBook();
        $author->setNbBook(max(0, $currentNbBooks - 1)); 
    }

    $em->remove($book);
    $em->flush();

    $this->addFlash('success', 'Livre supprimé avec succès !');
    return $this->redirectToRoute('book_list');
}
#[Route('/book/show/{id}', name: 'book_show')]
public function showBook(BookRepository $bookRepository, int $id): Response
{
    $book = $bookRepository->find($id);

    if (!$book) {
        throw $this->createNotFoundException('Livre non trouvé');
    }

    return $this->render('book/show.html.twig', [
        'book' => $book,
    ]);
}

}
