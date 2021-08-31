<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends BaseFixture
{
    public function loadData(ObjectManager $em)
    {
        $this->createMany(Article::class, 10, function (Article $article, $count) {
            $article->setTitle("Why Asteroids Taste Like Bacon")
                ->setSlug("why-asteroids-taste-like-bacon-".$count)
                ->setContent(<<<EOF
**Ejemplo** de contenido.
EOF
                );

            if (rand(1, 10) > 2) {
                $article->setPublishedAt(new \DateTimeImmutable(sprintf('-%d days', rand(1, 100))));
            }

            $article->setAuthor("Mike Ferengi")
                ->setHeartCount(rand(4, 100))
                ->setImageFilename('asteroid.jpeg');
        });

        $em->flush();
    }

}
