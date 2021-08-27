<?php

namespace App\Controller;

use App\Service\MarkdownHelper;
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
    public function homepage()
    {
        return $this->render('article/homepage.html.twig');
    }

    /**
     * @Route("/news/{slug}", name="article_show")
     * @throws \Http\Client\Exception
     */
    public function show($slug, MarkdownHelper $markdownHelper, Client $client)
    {
        if ($slug == 'khaaaaaan'){
            $message = $client->createMessage()
                ->from('Khan')
                ->withIcon(':ghost:')
                ->setText('Ah, Kirk, my old friend...');

            $Response = $client->sendMessage($message);
            dump($Response, $client);exit;
        }

        $comments = [
            'I ate a normal rock once. It did NOT taste like bacon!',
            'Woohoo! I\'m going on an all-asteroid diet!',
            'I like bacon too! Buy some from my site! bakinsomebacon.com',
        ];

        $articleContent = <<<EOF
Texto te de **ejemplo** que tengo que poner para que sirve para probar la interfaz que lo convierte en
 [markdown](https://google.com.ar) 
EOF;
        $articleContent = $markdownHelper->parse($articleContent);

        return $this->render('article/show.html.twig', [
            'title' => ucwords(str_replace('-', ' ', $slug)),
            'articleContent' => $articleContent,
            'slug' => $slug,
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
