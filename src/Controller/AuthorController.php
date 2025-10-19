<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


final class AuthorController extends AbstractController
{
    #[Route('/author/{name}', name: 'show_author')]
    public function show_author(string $name): Response
    {
       
        return $this->render('author/show.html.twig', [
'name' => $name,    ]);}


    #[Route('/list', name: 'author_list')]
    public function authorList(): Response
    {
        $authors = array(
            array('id' => 1, 'picture' => '/images/hugo.jpg', 'username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com ', 'nb_books' => 100),
            array('id' => 2, 'picture' => '/images/william.jpg', 'username' => ' William Shakespeare', 'email' =>  ' william.shakespeare@gmail.com', 'nb_books' => 200),
            array('id' => 3, 'picture' => '/images/taha.jpg', 'username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300),
        );


        return $this->render('author/list.html.twig', [
            'authors' => $authors,
        ]);
    }
 #[Route('/author/details/{id}', name: 'author_details')]
    public function authorDetails(int $id): Response
    {
        $authors = [
            1 => ['id' => 1, 'picture' => '/images/hugo.jpg','username' => 'Victor Hugo','email' => 'victor.hugo@gmail.com','nb_books' => 100],
            2 => ['id' => 2, 'picture' => '/images/william.jpg','username' => 'William Shakespeare','email' => 'william.shakespeare@gmail.com','nb_books' => 200],
            3 => ['id' => 3, 'picture' => '/images/taha.jpg','username' => 'Taha Hussein','email' => 'taha.hussein@gmail.com','nb_books' => 300],
        ];

        $author = $authors[$id] ?? null;

        return $this->render('author/index.html.twig', [
            'author' => $author
        ]);
    }
       #[Route('/authors', name: 'author_getAuthors', methods: ['GET'])]
    public function index (AuthorRepository $authorRepository): Response
    {

        // $authors = $authRepo->findAll();

        return $this->render('author/list.html.twig', [
            'authors' => $authorRepository->findAll(),
        ]);
    }
 #[Route('/add', name: 'author_addAuthor')]
  public function addAuthor(EntityManagerInterface $mr): Response
    {
        $author = new Author();
        $author->setUsername("abouelkassem");
        $author->setEmail("abouelkassem@gmail.com");
        $author->setnb_books(50);        
        $mr->persist($author);
        $mr->flush();

        return $this->redirectToRoute('author_getAuthors');
    }
    #[Route('/insert', name: 'author_insertAuthor')]
    public function insertAuthor(EntityManagerInterface $mr, Request $request): Response
    {
        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $mr->persist($author);
            $mr->flush();

            return $this->redirectToRoute('author_getAuthors');
        }

        return $this->render('author/form.html.twig', [
            'authorForm' => $form,
        ]);

    }
    #[Route('/update/{id}', name: 'author_updateAuthor')]
    public function updateAuthor(EntityManagerInterface $mr, Request $request, $id): Response
    {
        $author = new Author();
        $author = $mr->getRepository(Author::class)->find($id);
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $mr->persist($author);
            $mr->flush();

            return $this->redirectToRoute('author_getAuthors');
        }

        return $this->render('author/form.html.twig', [
            'authorForm' => $form,
        ]);
    }

    //delete author
    #[Route('/delete/{id}', name: 'author_delete')]
    public function delete(EntityManagerInterface $mr, $id): Response
    {
        $author = $mr->getRepository(Author::class)->find($id);
        $mr->remove($author);
        $mr->flush();

        return $this->redirectToRoute('author_getAuthors');
    }
}


