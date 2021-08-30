<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleAdminController extends AbstractController
{
    /**
     * @Route("/admin/article/new")
     */
    public function new(EntityManagerInterface $em): Response
    {
        $article = new Article();
        $article->setTitle("Why Asteroids Taste Like Bacon")
            ->setSlug("why-asteroids-taste-like-bacon-".rand(100, 999))
            ->setContent(<<<EOF
**Ejemplo** de contenido.
EOF
            );


        if (rand(1, 10) > 2) {
            $article->setPublishedAt(new \DateTimeImmutable(sprintf('-%d days', rand(1, 100))));
        }

        $em->persist($article);
        $em->flush();

        return new Response(sprintf(
            "Hiya! New article id: #%d slug: %s",
            $article->getId(),
            $article->getSlug()
        ));
    }
}
