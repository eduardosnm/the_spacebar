<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Service\MarkdownHelper;
use App\Service\SlackClient;
use Doctrine\ORM\EntityManagerInterface;
use Nexy\Slack\Client;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * Currently unused: jost showing a controller with a constructor.
     * 
     * @var bool
     */
    private $isDebug;

    public function __construct(bool $isDebug)
    {
        $this->isDebug = $isDebug;
    }

    /**
     * @Route("/", name="app_homepage")
     */
    public function homepage(ArticleRepository $repository)
    {
        $articles = $repository->findAllPublishedOrderedByNewest();

        return $this->render('article/homepage.html.twig', [
            "articles" => $articles
        ]);
    }

    /**
     * @Route("/news/{slug}", name="article_show")
     * @throws \Http\Client\Exception
     */
    public function show($slug, SlackClient $slackClient, EntityManagerInterface $em)
    {
        if ($slug == 'khaaaaaan'){
            $slackClient->sendMessage("eduardo", "this is a test");
        }

        $repository = $em->getRepository(Article::class);
        /** @var  $article */
        $article = $repository->findOneBy(["slug" => $slug]);
        if (!$article) {
            throw $this->createNotFoundException(sprintf('No article for slug "%s"', $slug));
        }

        $comments = [
            'I ate a normal rock once. It did NOT taste like bacon!',
            'Woohoo! I\'m going on an all-asteroid diet!',
            'I like bacon too! Buy some from my site! bakinsomebacon.com',
        ];


        return $this->render('article/show.html.twig', [
            "article" => $article,
            'comments' => $comments,
        ]);
    }

    /**
     * @Route("/news/{slug}/heart", name="article_toggle_heart", methods={"POST"})
     */
    public function toggleArticleHeart($slug, LoggerInterface $logger)
    {
        // TODO - actually heart/unheart the article!

        $logger->info('Article is being hearted!');

        return new JsonResponse(['hearts' => rand(5, 100)]);
    }
}
