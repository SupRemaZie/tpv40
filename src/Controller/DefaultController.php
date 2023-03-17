<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

use Psr\Log\LoggerInterface;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;

use App\Entity\Catalogue\Livre;
use App\Entity\Panier\Panier;
use App\Entity\Panier\LignePanier;

use Doctrine\ORM\EntityManagerInterface;

class DefaultController extends AbstractController
{
	private $entityManager;
	private $logger;
	
	public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)  {
		$this->entityManager = $entityManager;
		$this->logger = $logger;
	}

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        //return $this->render('default/index.html.twig', [
        //    'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        //]) // test
		return $this->redirectToRoute('afficheRecherche');
    }
	
    /**
     * @Route("/test1", name="test1")
     */
    public function test1Action(Request $request)
    {
        $panier = new Panier() ;
		$livre = $this->entityManager->getReference("App\Entity\Catalogue\Livre", "1141555897821");
		$panier->ajouterLigne($livre);
		$livre = $this->entityManager->getReference("App\Entity\Catalogue\Livre", "1141556299459");
		$panier->ajouterLigne($livre);
		// Affichage du contenu du panier:
		$it = $panier->getLignesPanier()->getIterator();
		$out = "" ;
		while ($it->valid()) {
			$ligne = $it->current();
			$it->next();
			$livre = $ligne->getLivre();
			$out .= $livre->getRefLivre();
			$out .= " - ";
			$out .= $livre->getAuteur();
			$out .= " - ";
			$out .= $ligne->getPrixUnitaire();
			$out .= " - ";
			$out .= $ligne->getPrixTotal();
			$out .= " - ";
			$out .= $ligne->getQuantite();
			$out .= "\n";
		}
		$out .= $panier->getTotal();
		$out .= "\n";
		
		$response = new Response() ;
		$response->headers->set("Content-Type", "text/plain") ;
		$response->setContent($out) ;
        return $response ;
    }
	
    /**
     * @Route("/test2", name="test2")
     */
    public function test2Action(Request $request)
    {
        $panier = new Panier() ;
		$livre = $this->entityManager->getReference("App\Entity\Catalogue\Livre", "1141555897821");
		$panier->ajouterLigne($livre);
		$livre = $this->entityManager->getReference("App\Entity\Catalogue\Livre", "1141556299459");
		$panier->ajouterLigne($livre);
		
		// Affichage du contenu du panier:
		$it = $panier->getLignesPanier()->getIterator();
		$out = "" ;
		while ($it->valid()) {
			$ligne = $it->current();
			$it->next();
			$livre = $ligne->getLivre();
			$out .= $livre->getRefLivre();
			$out .= " - ";
			$out .= $livre->getAuteur();
			$out .= " - ";
			$out .= $ligne->getPrixUnitaire();
			$out .= " - ";
			$out .= $ligne->getPrixTotal();
			$out .= " - ";
			$out .= $ligne->getQuantite();
			$out .= "\n";
		}
		$out .= $panier->getTotal();
		$out .= "\n";
		
		$out .=  "panier.ajouterLigne(livre)" ;
		$out .= "\n";
		$livre = $this->entityManager->getReference("App\Entity\Catalogue\Livre", "1141556299459");
		$panier->ajouterLigne($livre);
		
		// Affichage du contenu du panier:
		$it = $panier->getLignesPanier()->getIterator();
		while ($it->valid()) {
			$ligne = $it->current();
			$it->next();
			$livre = $ligne->getLivre();
			$out .= $livre->getRefLivre();
			$out .= " - ";
			$out .= $livre->getAuteur();
			$out .= " - ";
			$out .= $ligne->getPrixUnitaire();
			$out .= " - ";
			$out .= $ligne->getPrixTotal();
			$out .= " - ";
			$out .= $ligne->getQuantite();
			$out .= "\n";
		}
		$out .= $panier->getTotal();
		$out .= "\n";

		$out .=  "panier.supprimerLigne(\"1141556299459\")" ;
		$out .= "\n";
		$panier->supprimerLigne("1141556299459");
		
		// Affichage du contenu du panier:
		$it = $panier->getLignesPanier()->getIterator();
		while ($it->valid()) {
			$ligne = $it->current();
			$it->next();
			$livre = $ligne->getLivre();
			$out .= $livre->getRefLivre();
			$out .= " - ";
			$out .= $livre->getAuteur();
			$out .= " - ";
			$out .= $ligne->getPrixUnitaire();
			$out .= " - ";
			$out .= $ligne->getPrixTotal();
			$out .= " - ";
			$out .= $ligne->getQuantite();
			$out .= "\n";
		}
		$out .= $panier->getTotal();
		$out .= "\n";
		
		$out .=  "panier.viderPanier()" ;
		$out .= "\n";
		$panier->viderPanier();
		
		$it = $panier->getLignesPanier()->getIterator();
		if ($it->valid())
			$out .=  "Non vide ???" ;
		else
			$out .=  "Vide" ;
		$out .= "\n";
		
		$response = new Response() ;
		$response->headers->set("Content-Type", "text/plain") ;
		$response->setContent($out) ;
        return $response ;
    }
}
