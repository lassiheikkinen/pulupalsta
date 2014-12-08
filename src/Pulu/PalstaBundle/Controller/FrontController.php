<?php
namespace Pulu\PalstaBundle\Controller;

use Pulu\PalstaBundle\Entity\Article;
use Pulu\PalstaBundle\Entity\ArticleLocalization;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FrontController extends Controller {

    public function indexAction() {
        $repository = $this->getDoctrine()->getRepository('PuluPalstaBundle:Article');
        $repository->setLanguage($this->getRequest()->getLocale());
        $recentArticles = $repository->findOrderedByCreatedForPublic(10);
        $visitedArticles = $repository->findOrderedByVisitsForPublic(10);
        $keywords = $this->getKeywords();       

        return $this->render('PuluPalstaBundle:Front:index.html.php', array(
            'recentArticles' => $recentArticles,
            'visitedArticles' => $visitedArticles,
            'keywords' => $keywords
        ));
    }

    protected function getKeywords() {
        $keywords = $this->getDoctrine()->getRepository('PuluPalstaBundle:Keyword')->findAllOrderedByName();

        $returnedAmount = 50;
        $numberOfClasses = 6;

        // init basic data
        $out = array();
        foreach ($keywords as $keyword) {
            $weight = $keyword->getWeight();
            if ($weight > 0) {
                $out[] = array(
                    'name' => $keyword->getName($this->getRequest()->getLocale()),
                    'weight' => $weight,
                    'normalized_weight' => null,
                    'id' => $keyword->getId()
                );
            }
        }

        // return X number of random keywords divided to six weight classes
        shuffle($out);
        $out = array_slice($out, 0, $returnedAmount);
        usort($out, function ($a, $b) {
            if ($a['weight'] == $b['weight']) {
                return 0;
            }
            return $a['weight'] > $b['weight'] ? 1 : -1;
        });

        foreach ($out as $key => $row) {
            for ($class = 1; $class <= $numberOfClasses; $class++) {
                if ($key < ($returnedAmount / $numberOfClasses) * $class) {
                    $out[$key]['normalized_weight'] = $class;
                    break;
                }
            }
        }
        shuffle($out);

        return $out;
    }

}
