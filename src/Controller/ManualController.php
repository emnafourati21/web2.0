<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\component\Brother\Exception\BrotherException;
use Symfony\Component\HttpFoundation\Response;


class ManualController extends AbstractController
{
    #[Route("/hello", name: "app_hello")]
    public function Hello(){
        return new Response("hello every body!!");
    }
}