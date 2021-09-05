<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Comment;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends BaseFixture
{
    private static $articleImages = [
        'asteroid.jpeg',
        'mercury.jpeg',
        'lightspeed.png',
    ];
    public function loadData(ObjectManager $em)
    {
        $this->createMany(Article::class, 10, function (Article $article, $count) use ($em) {
            $article->setTitle($this->faker->words(7, true))
                ->setContent(<<<EOF
**Ejemplo** de contenido.
EOF
                );

            if ($this->faker->boolean(70)) {
                $article->setPublishedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
            }

            $article->setAuthor($this->faker->name)
                ->setHeartCount($this->faker->numberBetween(5, 100))
                ->setImageFilename($this->faker->randomElement(self::$articleImages));

            $comment1 = new Comment();
            $comment1->setAuthorName("Anonimo");
            $comment1->setContent("content");
//            $comment1->setArticle($article);
            $em->persist($comment1);

            $comment2 = new Comment();
            $comment2->setAuthorName("Anonimo");
            $comment2->setContent("otro contenido");
//            $comment2->setArticle($article);
            $em->persist($comment2);

            $article->addComment($comment1);
            $article->addComment($comment2);

        });

        $em->flush();
    }

}
