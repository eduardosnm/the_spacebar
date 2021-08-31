<?php

namespace App\DataFixtures;

use App\Entity\Article;
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
        $this->createMany(Article::class, 10, function (Article $article, $count) {
            $article->setTitle($this->faker->words(7, true))
                ->setSlug($this->faker->slug)
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
        });

        $em->flush();
    }

}
