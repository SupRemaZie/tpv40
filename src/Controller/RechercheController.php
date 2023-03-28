<?php

namespace App\Controller;

use App\Entity\Catalogue\Article;
use PHPUnit\Util\Xml\Validator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Psr\Log\LoggerInterface;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Catalogue\Livre;
use App\Entity\Catalogue\Musique;
use App\Entity\Catalogue\Piste;
use function PHPUnit\Framework\matches;

class RechercheController extends AbstractController
{
    private $entityManager;
    private $logger;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    /**
     * @Route("/afficheRecherche", name="afficheRecherche")
     */
    public function afficheRechercheAction(Request $request)
    {
        $query = $this->entityManager->createQuery("SELECT a FROM App\Entity\Catalogue\Article a");
        $articles = $query->getResult();
        return $this->render('recherche.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/afficheRechercheParMotCle", name="afficheRechercheParMotCle")
     */
    public function afficheRechercheParMotCleAction(Request $request)
    {


        $query = $this->entityManager->createQueryBuilder()
            ->select('a')
            ->from(Musique::class, 'a')
            ->where('a.artiste LIKE :keyword')
            ->setParameter('keyword', '%' . $request->query->get("motCle") . '%');


        if (count($query->getQuery()->getResult()) == null || count($query->getQuery()->getResult()) == 0) {
            $query = $this->entityManager->createQueryBuilder()
                ->select('a')
                ->from(Livre::class, 'a')
                ->where('a.auteur LIKE :keyword')
                ->setParameter('keyword', '%' . $request->query->get("motCle") . '%');
        }

        if (count($query->getQuery()->getResult()) == null || count($query->getQuery()->getResult()) == 0) {
            $query = $this->entityManager->createQueryBuilder()
                ->select('a')
                ->from(Article::class, 'a')
                ->where('a.titre LIKE :keyword')
                ->setParameter('keyword', '%' . $request->query->get("motCle") . '%');


        }
        $articles = $query->getQuery()->getResult();
        return $this->render('recherche.html.twig', [
            'articles' => $articles,
        ]);
    }
}

