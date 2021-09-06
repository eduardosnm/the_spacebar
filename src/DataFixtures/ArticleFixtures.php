<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Tag;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends BaseFixture implements DependentFixtureInterface
{
    private static $articleImages = [
        'asteroid.jpeg',
        'mercury.jpeg',
        'lightspeed.png',
    ];

    protected function loadData(ObjectManager $em)
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

            /** @var Tag[] $tags */
            $tags = $this->getRandomReferences(Tag::class, $this->faker->numberBetween(0,5));
            foreach ($tags as $tag){
                $article->addTag($tag);
            }

        });

        $em->flush();
    }

    public function getDependencies()
    {
        return [
            TagFixture::class
        ];
    }
}
