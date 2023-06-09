<?php

namespace App\Entity\Panier;

use \ArrayObject;

/**
 * Panier
 */
class Panier
{
    /**
     * @var total
     */
    private $total;

    /**
     * @var ArrayObject
     */
    private $lignesPanier;

	public function __construct()
    {
		$this->lignesPanier = new ArrayObject();
    }

	public function setTotal()
	{
		$this->recalculer();
    }
	
	public function getTotal()
	{
		$this->recalculer();
		return $this->total;
    }
	
	public function getLignesPanier() {
		return $this->lignesPanier;
	}
	
	public function recalculer() {
		$it = $this->getLignesPanier()->getIterator();
		$this->total = 0.0 ;
		while ($it->valid()) {
			$ligne = $it->current();
			$ligne->recalculer() ;
			$this->total += $ligne->getPrixTotal() ;
			$it->next();
		}
	}
	
	public function ajouterLigne($inArticle) {
		$lp = $this->chercherLignePanier($inArticle) ;
		if ($lp == null) {
			$lp = new LignePanier() ;
			$lp->setArticle($inArticle) ; 
			$lp->setQuantite(1) ;
			$this->lignesPanier->append($lp) ;
		}
		else
			$lp->setQuantite($lp->getQuantite() + 1) ;
		$this->recalculer() ;
	}
	
	public function chercherLignePanier($inArticle) {
		$lignePanier = null ;
		$it = $this->getLignesPanier()->getIterator();
		while ($it->valid()) {
			$ligne = $it->current();
			if ($ligne->getArticle()->getId() == $inArticle->getId())
				$lignePanier = $ligne ;
			$it->next();
		}
		return $lignePanier ;
	}
	
	public function supprimerLigne($id) {
		$existe = false ;
		$it = $this->getLignesPanier()->getIterator();
		while ($it->valid()) {
			$ligne = $it->current();
			if ($ligne->getArticle()->getId() == $id) {
				$existe = true ;
				$key = $it->key();
			}
			$it->next();
		}
		if ($existe)
			$this->getLignesPanier()->offsetUnset($key);
	}
}

