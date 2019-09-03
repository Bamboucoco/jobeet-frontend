<?php
// src/AL/PlatformBundle/Controller/AdvertControler.php

namespace AL\PlatformBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class AdvertController extends Controller
{
    // Fonction principale pour afficher les annonces de la page d'accueil
    // trie les annonces par catégorie et date, limite 3 annonces par catégorie
    // prépare aussi annonce pour mot clé envoyé par formulaire
    public function indexAction($page)
    {
        $listAdverts = array(
            // 0 = 'id'
            // 1 = 'cat'
            // 2 = 'title'
            // 3 = 'location'
            // 4 = 'position'
            // 5 = 'company'
            // 6 = 'author'
            // 7 = 'content'
            // 8 = 'date' au format 'mm/dd/yyyy'
            array(
                1,
                'Programming',
                'Recherche dév symfony CDD 1 mois voir plus',
                'Réunion France',
                'Développeur web',
                'OpenClassRoom',
                'Alexandre',
                'Nous recherchons un bon développeur sur Symfony, Région Réunion et Mayotte. CDD de un mois pour commencer',
                '03/02/2019'
            ),
            array(
                2,
                'Programming',
                'MISSION webmaster Wordpress',
                'Paris France',
                'Webmaster',
                'DevToi',
                'Hugo',
                'Nous recherchons un webmaster confirmé sur Wordpress, Région Paris. CDI',
                '03/01/2019'
            ),
            array(
                3,
                'Design',
                'STAGE webdesigner',
                'Réunion France',
                'Web designer',
                'BB Store',
                'Manuella',
                'Nous proposons un stage sur Adobe Photoshop , Région Réunion, 3 mois',
               '08/25/2019'
            ),
            array(
                4,
                'Programming',
                'Recherche dév Php CDD 3 mois ',
                'Réunion France',
                'Développeur web',
                'MIT',
                'Alexandre',
                'Nous recherchons un développeur PHP qui maitrise également CSS et javascript, Région Réunion et Mayotte. CDI',
                '08/20/2019',
            ),
            array(
                5,
                'Programming',
                'Webmaster site',
                'Paris France',
                'Webmaster',
                'DevToi',
                'Hugo',
                'Nous recherchons un webmaster débutant pour notre site, Région Paris. ',
                '09/02/2019'
            ),
            array(
                6,
                'Design',
                'Web designer',
                'Réunion France',
                'Web designer',
                'BB Store',
                'Manuella',
                'Nous recherchons un web designer, vous encadrerez 15 stagiaire sur Adobe Photoshop , Région Réunion',
                '09/01/2019',
            ),
            array(
                7,
                'Formation',
                'Recherche formateur symfony CDD 2 mois voir plus',
                'Réunion France',
                'Développeur web',
                'OpenClassRoom',
                'Alexandre',
                'Nous recherchons un bon développeur sur Symfony, Région Réunion et Mayotte. CDD de un mois pour commencer',
                '03/04/2019'
            ),
            array(
                8,
                'Programming',
                'MISSION webmaster Wordpress expert',
                'Paris France',
                'Webmaster',
                'DevToi',
                'Hugo',
                'Nous recherchons un webmaster trés trés  confirmé sur Wordpress, Région Paris. CDI',
                '03/02/2019'
            ),
            array(
                9,
                'Design',
                'STAGE webdesigner et plus encore',
                'Réunion France',
                'Web designer',
                'BB Store',
                'Manuella',
                'Nous proposons 20 stages sur Adobe Photoshop , Région Réunion, 1 mois',
                '08/26/2019'
            ),
            array(
                10,
                'Programming',
                'Recherche dév Php CDD 13 mois ',
                'Réunion France',
                'Développeur web',
                'MIT',
                'Alexandre',
                'Nous recherchons quelque développeurs PHP qui maitrisent javascript, Région Réunion et Mayotte. CDI',
                '08/20/2019',
            ),
            array(
                11,
                'Programming',
                'Webmaster site',
                'Paris France',
                'Webmaster',
                'DevToi',
                'Hugo',
                'Nous recherchons 2 webmaster débutant pour notre site, Région Paris. ',
                '09/02/2019'
            ),
            array(
                12,
                'Design',
                'Web designer',
                'Réunion France',
                'Web designer',
                'BB Store',
                'Manuella',
                'Nous recherchons un web designer, vous encadrerez 1 stagiaire sur Adobe Photoshop , Région Réunion',
                '09/01/2019',
            )
        );

        //SELECTION CONTENU A AFFICHER PAR DEFAUT
        //1) TRIER listAdverts par catégorie et par date,
        // 2) 3 advert max à afficher par catégorie
        foreach ($listAdverts as $key => $row) { //#1
            //$cat[$key] = $row['cat'];
            $cat[$key] = $row[1];
            //$date[$key] = $row['date'];
            $date[$key] = $row[8];
        }
        array_multisort($cat, SORT_ASC, $date, SORT_DESC, $listAdverts);

        //Mise en mémoire via session du tableau des annonces, trié par catégorie et par date
        if (isset($session)) {
        }
        else{
            $session=$this->get('session');
            $session->set('listAdverts', $listAdverts);
        }

        //Limiter nb enregistrement à affciher par catégorie
        $category="";
        $nb_advert=0;
        $tab_listAdvertsMin=[];

        foreach ($listAdverts as $advert)
        {
            //if ($category == $advert['cat'])
            if ($category == $advert[1])
            {
                //#2
                $nb_advert = $nb_advert + 1;
                //Test 3 annonces max par catégorie
                if ($nb_advert<4){
                    $tab_listAdvertsMin[]=$advert;
                }

            }
            else { // on recommence sur une nouvelle catégorie
                $nb_advert = 1;
                //$category = $advert['cat'];
                $category = $advert[1];
                $tab_listAdvertsMin[]=$advert;
            }
        }

        return $this->render('ALPlatformBundle:Advert:index.html.twig', array('listAdverts'=>$tab_listAdvertsMin
        ));
    }


    public function indexCatAction($cat)
    {// Fonction pour préparer affichage des annonces d'une catégorie

       //on récupère la liste des annonces triées par catégorie et par date via objet session
        //session initialisée au début du site via appel indexAction
       $session=$this->get('session');
       $listAdverts= $session->get('listAdverts') ;

        //initialise tableau qui va récupérer que les annonces de la catégorie sélectionnée
        $tab_listAdverts=[];

        foreach ($listAdverts as $advert)
        {
            if ($cat == $advert[1])
            {
                $tab_listAdverts[]=$advert;
            }
        }

        return $this->render('ALPlatformBundle:Advert:indexCat.html.twig', array('listAdverts'=>$tab_listAdverts
        ));
    }

    public function viewAction($id)
   {// Fonction pour sélectionner l'annonce correspondant à l'id sélectionné depuis page accueil

       //on récupère la liste des annonces triées par catégorie et par date via objet session
       //session initialisée au début du site via appel indexAction
       $session=$this->get('session');
       $listAdverts= $session->get('listAdverts') ;

       foreach ($listAdverts as $advert)
       {
           if ($id == $advert[0])
           {
               return $this->render('ALPlatformBundle:Advert:view.html.twig', array('advert'=>$advert));
           }
       }
   }

    public function rechercherAction(Request $request)
    { //on récupère la liste des annonces triées par catégorie et par date via objet session
      //session initialisée au début du site via appel indexAction
        $session=$this->get('session');
        $listAdverts= $session->get('listAdverts') ;

        //TEST FORMULAIRE RECHERCHER PAR MOT CLE
        if ($request->isMethod('POST'))
        {// Formulaire a été envoyé pour filtrer ¤listAdverts avec mot clé

            $motcle=$request->request->get('motcle');
            $test=0;
            //initialise tableau qui va récupérer que les annonces de la catégorie sélectionnée
            $tab_listAdverts=[];

            foreach ($listAdverts as $advert)
            {// transforme chaque enregistrement d'une annonce en chaine de texte
               $txtadvert=implode($advert);
               // recherche mot clé dans chaine de texte de l'annonce, sans tenir compte de la casse
                if (stristr($txtadvert,$motcle))
                {// si le mot clé est trouvé, on mémorise l'annonce dans un tableau qui sera retourné
                    $tab_listAdverts[]=$advert;
                    $test=$test+1;

                }
            }
            return $this->render('ALPlatformBundle:Advert:rechercher.html.twig', array('listAdverts'=>$tab_listAdverts
            ,'motcle'=>$motcle,'test'=>$test));

        }
        return $this->render('ALPlatformBundle:Advert:index.html.twig');
    }

}

