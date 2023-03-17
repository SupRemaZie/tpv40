<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use ApaiIO\Configuration\GenericConfiguration;
use ApaiIO\ApaiIO;
use ApaiIO\Operations\Search as AmazonSearch;
use ApaiIO\Request\GuzzleRequestWithoutKeys;
use GuzzleHttp\Client;

use DeezerAPI\Search as DeezerSearch;

use App\Entity\Catalogue\Livre;
use App\Entity\Catalogue\Musique;
use App\Entity\Catalogue\Piste;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
		if (count($manager->getRepository("App\Entity\Catalogue\Article")->findAll()) == 0) {
			$conf = new GenericConfiguration();
			$client = new Client();
			$request = new GuzzleRequestWithoutKeys($client);

			try {
				/*$conf
					->setCountry('de')
					->setAccessKey(AWS_API_KEY)
					->setSecretKey(AWS_API_SECRET_KEY)
					->setAssociateTag(AWS_ASSOCIATE_TAG);*/
				$conf
					->setCountry('fr')
					->setRequest($request) ;
			} catch (\Exception $e) {
				echo $e->getMessage();
			}
			$apaiIO = new ApaiIO($conf);

			$search = new AmazonSearch();
			$search->setCategory('Music');
			$keywords = 'Ibrahim Maalouf' ;
			
			//$search->setCategory('Books');
			//$keywords = 'Henning Mankell' ;
			
			$search->setKeywords($keywords);
			
			$search->setResponseGroup(array('Offers','ItemAttributes','Images'));

			$formattedResponse = $apaiIO->runOperation($search);

			$xml = simplexml_load_string($formattedResponse);
			if ($xml !== false) {
				foreach ($xml->children() as $child_1) {
					if ($child_1->getName() === "Items") {
						foreach ($child_1->children() as $child_2) {
							if ($child_2->getName() === "Item") {
								if ($child_2->ItemAttributes->ProductGroup->__toString() === "Book") {
									$entityLivre = new Livre();
									$entityLivre->setId($child_2->ASIN);
									$entityLivre->setTitre($child_2->ItemAttributes->Title);
									$entityLivre->setAuteur($child_2->ItemAttributes->Author);
									$entityLivre->setISBN($child_2->ItemAttributes->ISBN);
									$entityLivre->setPrix($child_2->OfferSummary->LowestNewPrice->Amount/100.0); 
									$entityLivre->setDisponibilite(1);
									$entityLivre->setImage($child_2->LargeImage->URL);
									$manager->persist($entityLivre);
									$manager->flush();
								}
								if ($child_2->ItemAttributes->ProductGroup->__toString() === "Music") {
									$entityMusique = new Musique();
									$entityMusique->setId($child_2->ASIN);
									$entityMusique->setTitre($child_2->ItemAttributes->Title);
									$entityMusique->setArtiste($child_2->ItemAttributes->Artist);
									$entityMusique->setDateDeParution($child_2->ItemAttributes->PublicationDate);
									$entityMusique->setPrix($child_2->OfferSummary->LowestNewPrice->Amount/100.0); 
									$entityMusique->setDisponibilite(1);
									$entityMusique->setImage($child_2->LargeImage->URL) ;
									if (!isset($albums)) {
										$deezerSearch = new DeezerSearch($keywords);
										$artistes = $deezerSearch->searchArtist() ;
										$albums = $deezerSearch->searchAlbumsByArtist($artistes[0]->getId()) ;
									}
									$j = 0 ;
									$sortir = ($j==count($albums)) ;
									$albumTrouve = false ;
									while (!$sortir) {
										$titreDeezer = str_replace(" ","",mb_strtolower($albums[$j]->title)) ;
										$titreAmazon = str_replace(" ","",mb_strtolower($entityMusique->getTitre())) ;
										$titreDeezer = str_replace("-","",$titreDeezer) ;
										$titreAmazon = str_replace("-","",$titreAmazon) ;
                                        $albumTrouve = ($titreDeezer == $titreAmazon) ;
                                        if (mb_strlen($titreAmazon) > mb_strlen($titreDeezer))
                                            $albumTrouve = $albumTrouve || (mb_strpos($titreAmazon, $titreDeezer) !== false) ;
                                         if (mb_strlen($titreDeezer) > mb_strlen($titreAmazon))
                                            $albumTrouve = $albumTrouve || (mb_strpos($titreDeezer, $titreAmazon) !== false) ;
										$j++ ;
										$sortir = $albumTrouve || ($j==count($albums)) ;
									}
									if ($albumTrouve) {
										$tracks = $deezerSearch->searchTracksByAlbum($albums[$j-1]->getId()) ;
										foreach ($tracks as $track) {
											$entityPiste = new Piste();
											$entityPiste->setTitre($track->title);
											$entityPiste->setMp3($track->preview);
											$manager->persist($entityPiste);
											$manager->flush();
											$entityMusique->addPiste($entityPiste) ;
										}
									}
									$manager->persist($entityMusique);
									$manager->flush();
								}
							}
						}
					}
				}
				$entityLivre = new Livre();
				$entityLivre->setId("1141555677821");
				$entityLivre->setTitre("Le seigneur des anneaux");
				$entityLivre->setAuteur("J.R.R. TOLKIEN");
				$entityLivre->setISBN("2075134049");
				$entityLivre->setNbPages(736);
				$entityLivre->setDateDeParution("03/10/19");
				$entityLivre->setPrix("8.90");
				$entityLivre->setDisponibilite(1);
				$entityLivre->setImage("/images/51O0yBHs+OL._SL500_.jpg");
				$manager->persist($entityLivre);
				$entityLivre = new Livre();
				$entityLivre->setId("1141555897821");
				$entityLivre->setTitre("Un paradis trompeur");
				$entityLivre->setAuteur("Henning Mankell");
				$entityLivre->setISBN("275784797X");
				$entityLivre->setNbPages(400);
				$entityLivre->setDateDeParution("09/10/14");
				$entityLivre->setPrix("6.80");
				$entityLivre->setDisponibilite(1);
				$entityLivre->setImage("/images/41kQXtYSFPL._SL500_.jpg");
				$manager->persist($entityLivre);
				$entityLivre = new Livre();
				$entityLivre->setId("1141556299459");
				$entityLivre->setTitre("Dôme tome 1");
				$entityLivre->setAuteur("Stephen King");
				$entityLivre->setISBN("2212110685");
				$entityLivre->setNbPages(840);
				$entityLivre->setDateDeParution("06/03/13");
				$entityLivre->setPrix("8.90");
				$entityLivre->setDisponibilite(1);
				$entityLivre->setImage("/images/41ICy6+-LdL._SL500_.jpg");
				$manager->persist($entityLivre);
				$manager->flush();
			}
		}
    }
}
