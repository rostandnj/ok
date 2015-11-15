<?php

namespace Sdz\ATIBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpFoundation\Response;
use Sdz\ATIBundle\Entity\Produit;
use Sdz\ATIBundle\Form\ProduitType;
use Sdz\ATIBundle\Form\ProduitEditType;
use Sdz\ATIBundle\Entity\Magasin;
use Sdz\ATIBundle\Form\MagasinType;
use Sdz\ATIBundle\Form\MagasinEditType;
use Sdz\UserBundle\Entity\User;
use Sdz\UserBundle\Entity\Role;
use Sdz\UserBundle\Form\UserType;
use Sdz\UserBundle\Form\UserEditType;
use Sdz\UserBundle\Form\UserPersonnalEditType;
use Sdz\ATIBundle\Entity\ProduitMagasin;
use Sdz\ATIBundle\Form\ProduitMagasinType;
use Sdz\ATIBundle\Form\ProduitMagasinEditType;
use Sdz\ATIBundle\Form\ProduitMagasinAjoutType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Sdz\ATIBundle\Entity\Entree;
use Sdz\ATIBundle\Entity\Sortie;
use Sdz\ATIBundle\Entity\Statistique;
use Sdz\ATIBundle\Entity\StatMagasin;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Ob\HighchartsBundle\Highcharts\Highchart;





class gestionController extends Controller
{
    
    private function randomcodeAction($car)
    {
        $string = "";
        $chaine = "VA1aC2bcWd3NU4ef5gX6Th8OJ7YB9SIZPijkRGlQmnHpqFrDsJtuKvwxLy/M*-&_!:;.";
        srand((double)microtime()*1000000);
        for($i=0; $i<$car; $i++) 
        {
            $string .= $chaine[rand()%strlen($chaine)];
        }

        return $string;

    }
 



    public function indexAction()
    {
        

        $date = new \Datetime();
        $heure = $date->format('d');

        $em = $this->getDoctrine()->getManager();
        $produits = $em->getRepository('SdzATIBundle:Produit')->getProduitlimit(10);

        if($produits == null)
        {
          return $this->render('SdzATIBundle:ati:gestion.html.twig');
        }
        $noms ="Les produits :";
        foreach ($produits as $produit) 
            {
                $noms = $noms." ".$produit->getNom()."," ;

            }
        $noms= $noms." ont un stock en dessous de 10 unités. Pensez à les approvisionner";
       
        $this->get('session')->getFlashBag()->add('infoalerte', $noms);
   

        return $this->render('SdzATIBundle:ati:gestion.html.twig',array('heure'=>$heure));
    }

    

    public function EntreeAction($page)
    {
        
        if ($this->get('security.context')->isGranted('ROLE_EMPLOYE') === false)
        {
            throw new AccessDeniedHttpException("Accès limité - bien vouloir vous authentifier");
            
        }

        $em = $this->getDoctrine()->getManager();
        $entrees = $em->getRepository('SdzATIBundle:Entree')
                       ->getEntrees(5,$page);
                         $nb=ceil(count($entrees)/5);

        return $this->render('SdzATIBundle:ati:gestion_entree.html.twig',array('entrees' => $entrees, 'page' => $page,'nombrePage' => $nb));
    }

    public function SortieAction($page)
    {
        
        if ($this->get('security.context')->isGranted('ROLE_EMPLOYE') === false)
        {
            throw new AccessDeniedHttpException("Accès limité - bien vouloir vous authentifier");
            
        }

        $em = $this->getDoctrine()->getManager();
        $sorties = $em->getRepository('SdzATIBundle:Sortie')
                       ->getSorties(5,$page);
                         $nb=ceil(count($sorties)/5);

        return $this->render('SdzATIBundle:ati:gestion_sortie.html.twig',array('sorties' => $sorties,'page' => $page, 'nombrePage' => $nb));
    }



    public function magasinAction($page)
    {
        if ($this->get('security.context')->isGranted('ROLE_EMPLOYE') === false)
        {
            throw new AccessDeniedHttpException("Accès limité - bien vouloir vous authentifier");
            
        }

        $magasins = $this->getDoctrine()
                         ->getManager()
                         ->getRepository('SdzATIBundle:Magasin')
                         ->getMagasins(5,$page);
                         $nb=ceil(count($magasins)/5);


        return $this->render('SdzATIBundle:ati:magasin.html.twig',array('magasins' => $magasins,'page' => $page,'nombrePage' =>$nb));
    }

    public function magasin_afficherAction(Magasin $magasin)
    {
        
       

        if($magasin === null)
        {
            throw $this->createNotFoundException('Magasin[id='.$id.']inexistant.');
        }
        
        
        return $this->render('SdzATIBundle:ati:magasin_afficher.html.twig',array('magasin'=>$magasin));
        
        
    }

    public function magasin_supprimerAction(Magasin $magasin)
    {
        
        if ($this->get('security.context')->isGranted('ROLE_ADMIN') === false)
        {
            throw new AccessDeniedHttpException("Accès limité - Vous ne disposez pas d'autorisation necessaire");
            
        }

        $form = $this->createFormBuilder()->getForm();
        
        $request = $this->getRequest();
        
        
        if ($request->getMethod() == 'POST')
        {
            $form->bind($request);
            if ($form->isValid())
            {
                // On supprime 
                $em = $this->getDoctrine()->getManager();
                $em->remove($magasin);
                $em->flush();
                
                
                
                return $this->redirect($this->generateUrl('sdz_ati_magasin'));
            }
        }
        
        return $this->render('SdzATIBundle:ati:magasin_suppr.html.twig',array('magasin' => $magasin,'form' => $form->createView()));
        
    }

    public function magasin_provisionAction(ProduitMagasin $pm)
    {

        if ($this->get('security.context')->isGranted('ROLE_GESTIONNAIRE') === false)
        {
            throw new AccessDeniedHttpException("Accès limité - Vous ne disposez pas d'autorisation necessaire");
            
        }

        
        $form = $this->createForm(new ProduitMagasinType, $pm);

        if($pm === null)
        {
            throw $this->createNotFoundException('ProduitMagasin[id='.$id.']inexistant.');
        }

        $request = $this->get('request');
        if($request->getMethod() == 'POST')
        {
            $form->bind($request);
            if($form->isValid())
            {
                

                $em = $this->getDoctrine()->getManager();
                $pm->setQuantite($pm->getQuantite() + $pm->getVar());
                $pm->setDate(new \Datetime());

                $entree = new Entree();
                $entree->setUser($this->get('security.context')->getToken()->getUser());
                $entree->setMagasin($pm->getMagasin());
                $entree->setProduit($pm->getProduit());
                $entree->setQuantite($pm->getVar());
                $entree->setObservation($pm->getObservation());

                $produit = $em->getRepository('SdzATIBundle:Produit')->findOneById($pm->getProduit()->getId());
                $produit->setQtet($produit->getQtet() + $pm->getVar());
                $produit->setQted($produit->getQted() + $pm->getVar());

                $statm = $em->getRepository('SdzATIBundle:StatMagasin')->findStatMannee($pm->getMagasin()->getId(),$pm->getProduit()->getId(),$entree->getDate()->format('Y'));

                if ($statm == null)
                {
                   
                    $newstatm = new StatMagasin();
                    $newstatm->setMois($newstat->getDate()->format('m'));
                    $newstatm->setAnnee($newstat->getDate()->format('Y'));
                    $newstatm->setEntree($entree->getQuantite());
                    $newstatm->setSortie(0);
                    $newstatm->setFreqvente(0);
                    $newstatm->setProduit($pm->getProduit());
                    $newstatm->setMagasin($pm->getMagasin());


                    $stat = $em->getRepository('SdzATIBundle:Statistique')->findStatannee($pm->getProduit()->getId(),$entree->getDate()->format('Y'));

                    if ($stat == null)
                    {
                        $newstat = new Statistique();
                        $newstat->setMois($newstat->getDate()->format('m'));
                        $newstat->setAnnee($newstat->getDate()->format('Y'));
                        $newstat->setEntree($entree->getQuantite());
                        $newstat->setSortie(0);
                        $newstat->setFreqvente(0);
                        $newstat->setProduit($pm->getProduit());

                        $em->persist($newstatm);
                        $em->persist($newstat);
                        $em->persist($produit);
                        $em->persist($pm);
                        $em->persist($entree);
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('infom', 'Approvisionnement bien effectué');
                        return $this->redirect ($this->generateUrl('sdz_ati_gestion_magasin',array('id' => $pm->getMagasin()->getId())));
                    }

                    $stat2 = $em->getRepository('SdzATIBundle:Statistique')->findStatmois($pm->getProduit()->getId(),$entree->getDate()->format('Y'),$entree->getDate()->format('m'));

                    if ($stat2 === null)
                    {
                        $newstat = new Statistique();
                        $newstat->setMois($newstat->getDate()->format('m'));
                        $newstat->setAnnee($newstat->getDate()->format('Y'));
                        $newstat->setEntree($entree->getQuantite());
                        $newstat->setSortie(0);
                        $newstat->setFreqvente(0);
                        $newstat->setProduit($pm->getProduit());

                        $em->persist($newstatm);
                        $em->persist($newstat);
                        $em->persist($produit);
                        $em->persist($pm);
                        $em->persist($entree);
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('infom', 'Approvisionnement bien effectué');
                        return $this->redirect ($this->generateUrl('sdz_ati_gestion_magasin',array('id' => $pm->getMagasin()->getId())));
                    }

                    else
                    {
                        $stat2->setEntree($stat2->getEntree() + $entree->getQuantite());

                        $em->persist($newstatm);
                        $em->persist($stat2);
                        $em->persist($produit);
                        $em->persist($pm);
                        $em->persist($entree);
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('infom', 'Approvisionnement bien effectué');
                        return $this->redirect ($this->generateUrl('sdz_ati_gestion_magasin',array('id' => $pm->getMagasin()->getId())));
                    }


                }

                $statm2 = $em->getRepository('SdzATIBundle:StatMagasin')->findStatMmois($pm->getMagasin()->getId(),$pm->getProduit()->getId(),$entree->getDate()->format('Y'),$entree->getDate()->format('m'));

                if ($statm2 === null)
                {
                    
                    $newstatm = new StatMagasin();
                    $newstatm->setMois($newstat->getDate()->format('m'));
                    $newstatm->setAnnee($newstat->getDate()->format('Y'));
                    $newstatm->setEntree($entree->getQuantite());
                    $newstatm->setSortie(0);
                    $newstatm->setFreqvente(0);
                    $newstatm->setProduit($pm->getProduit());
                    $newstatm->setMagasin($pm->getMagasin());

                    $stat2 = $em->getRepository('SdzATIBundle:Statistique')->findStatmois($pm->getProduit()->getId(),$entree->getDate()->format('Y'),$entree->getDate()->format('m'));

                    if ($stat2 === null)
                    {
                        $newstat = new Statistique();
                        $newstat->setMois($newstat->getDate()->format('m'));
                        $newstat->setAnnee($newstat->getDate()->format('Y'));
                        $newstat->setEntree($entree->getQuantite());
                        $newstat->setSortie(0);
                        $newstat->setFreqvente(0);
                        $newstat->setProduit($pm->getProduit());

                        $em->persist($newstatm);
                        $em->persist($newstat);
                        $em->persist($produit);
                        $em->persist($pm);
                        $em->persist($entree);
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('infom', 'Approvisionnement bien effectué');
                        return $this->redirect ($this->generateUrl('sdz_ati_gestion_magasin',array('id' => $pm->getMagasin()->getId())));
                    }

                    else
                    {
                        $stat2->setEntree($stat2->getEntree() + $entree->getQuantite());

                        $em->persist($newstatm);
                        $em->persist($stat2);
                        $em->persist($produit);
                        $em->persist($pm);
                        $em->persist($entree);
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('infom', 'Approvisionnement bien effectué');
                        return $this->redirect ($this->generateUrl('sdz_ati_gestion_magasin',array('id' => $pm->getMagasin()->getId())));
                    }
                }

                else
                {
                    $statm2->setEntree($statm2->getEntree() + $entree->getQuantite());
                    $stat = $em->getRepository('SdzATIBundle:Statistique')->findStatmois($pm->getProduit()->getId(),$entree->getDate()->format('Y'),$entree->getDate()->format('m'));
                    $stat->setEntree($stat->getEntree() + $entree->getQuantite());



                    $em->persist($statm2);
                    $em->persist($stat);
                    $em->persist($produit);
                    $em->persist($pm);
                    $em->persist($entree);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('infom', 'Approvisionnement bien effectué');
                    return $this->redirect ($this->generateUrl('sdz_ati_gestion_magasin',array('id' => $pm->getMagasin()->getId())));


                }

                
            }

        }
           
           return $this->render('SdzATIBundle:ati:magasin_provision.html.twig',array('pm' => $pm,'form' => $form->createView()));
       
        
    }


    public function magasin_sortieAction(ProduitMagasin $pm)
    {

        if ($this->get('security.context')->isGranted('ROLE_GESTIONNAIRE') === false)
        {
            throw new AccessDeniedHttpException("Accès limité - Vous ne disposez pas d'autorisation necessaire");
            
        }
        $date = new\Datetime();

        $form = $this->createForm(new ProduitMagasinEditType, $pm);

        if($pm === null)
        {
            throw $this->createNotFoundException('ProduitMagasin[id='.$id.']inexistant.');
        }

        $request = $this->get('request');
        if($request->getMethod() == 'POST')
        {
            $form->bind($request);
            if ($pm->getVars() > $pm->getQuantite())
            {
                $msg = "Erreur: quantité entrée superieure au stock disponible";
                return $this->render('SdzATIBundle:ati:magasin_sortie.html.twig',array('msg' => $msg,'pm' => $pm,'form' => $form->createView()));
            }
            if($form->isValid())
            {
                
                $qd=$pm->getVars();
                $em = $this->getDoctrine()->getManager();
                $pm->setQuantite($pm->getQuantite() - $pm->getVars());
                $pm->setDate(new \Datetime());



                $sortie = new Sortie();
                $sortie->setUser($this->get('security.context')->getToken()->getUser());
                $sortie->setMagasin($pm->getMagasin());
                $sortie->setProduit($pm->getProduit());
                $sortie->setQuantite($qd);
                $sortie->setObservation($pm->getObservation());

                $produit = $em->getRepository('SdzATIBundle:Produit')->findOneById($pm->getProduit()->getId());
                $produit->setQted($produit->getQted() - $qd);

                $statm = $em->getRepository('SdzATIBundle:StatMagasin')->findStatMannee($pm->getMagasin()->getId(),$pm->getProduit()->getId(),$sortie->getDate()->format('Y'));

                if ($statm == null)
                {
                    $newstat = new Statistique();
                    $newstat->setMois($newstat->getDate()->format('m'));
                    $newstat->setAnnee($newstat->getDate()->format('Y'));
                    $newstat->setEntree(0);
                    $newstat->setSortie($sortie->getQuantite());
                    $newstat->setFreqvente($sortie->getQuantite());
                    $newstat->setProduit($pm->getProduit());

                    $newstatm = new StatMagasin();
                    $newstatm->setMois($newstat->getDate()->format('m'));
                    $newstatm->setAnnee($newstat->getDate()->format('Y'));
                    $newstatm->setEntree(0);
                    $newstatm->setSortie($sortie->getQuantite());
                    $newstatm->setFreqvente($sortie->getQuantite());
                    $newstatm->setProduit($pm->getProduit());
                    $newstatm->setMagasin($pm->getMagasin());


                    
                    $em->persist($newstatm);
                    $em->persist($newstat);
                    $em->persist($produit);
                    $em->persist($pm);
                    $em->persist($sortie);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('infom', 'Sortie du produit bien effectuée');
                    return $this->redirect ($this->generateUrl('sdz_ati_gestion_magasin',array('id' => $pm->getMagasin()->getId())));


                }

                $statm2 = $em->getRepository('SdzATIBundle:StatMagasin')->findStatMmois($pm->getMagasin()->getId(),$pm->getProduit()->getId(),$sortie->getDate()->format('Y'),$sortie->getDate()->format('m'));

                if ($statm2 === null)
                {
                    $newstat = new Statistique();
                    $newstat->setMois($newstat->getDate()->format('m'));
                    $newstat->setAnnee($newstat->getDate()->format('Y'));
                    $newstat->setEntree(0);
                    $newstat->setSortie($sortie->getQuantite());
                    $newstat->setFreqvente($sortie->getQuantite());
                    $newstat->setProduit($pm->getProduit());

                    $newstatm = new StatMagasin();
                    $newstatm->setMois($newstat->getDate()->format('m'));
                    $newstatm->setAnnee($newstat->getDate()->format('Y'));
                    $newstatm->setEntree(0);
                    $newstatm->setSortie($sortie->getQuantite());
                    $newstatm->setFreqvente($sortie->getQuantite());
                    $newstatm->setProduit($pm->getProduit());
                    $newstatm->setMagasin($pm->getMagasin());


                    
                    $em->persist($newstatm);
                    $em->persist($newstat);
                    $em->persist($produit);
                    $em->persist($pm);
                    $em->persist($sortie);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('infom', 'Sortie du produit bien effectuée');
                    return $this->redirect ($this->generateUrl('sdz_ati_gestion_magasin',array('id' => $pm->getMagasin()->getId())));


                }
                else
                {
                    $statm2->setSortie($statm2->getSortie() + $sortie->getQuantite());
                    $stat = $em->getRepository('SdzATIBundle:Statistique')->findStatmois($pm->getProduit()->getId(),$sortie->getDate()->format('Y'),$sortie->getDate()->format('m'));
                    $stat->setSortie($stat->getSortie() + $sortie->getQuantite());
                    $nb = $date->diff($statm2->getDate());
                    $nb = $nb->format('%d');
                    
                    if($nb ==0)
                    {
                        $statm2->setFreqvente($statm2->getSortie());
                        $stat->setFreqvente($stat->getSortie());
                    }
                    else
                    {
                       $statm2->setFreqvente($statm2->getSortie()/$nb);
                       $stat->setFreqvente($stat->getSortie()/$nb); 
                    }
                    
                    
                    



                    $em->persist($statm2);
                    $em->persist($stat);
                    $em->persist($produit);
                    $em->persist($pm);
                    $em->persist($sortie);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('infom', 'Sortie du produit bien effectuée');
                    return $this->redirect ($this->generateUrl('sdz_ati_gestion_magasin',array('id' => $pm->getMagasin()->getId())));
                }


            }

        }
           
           return $this->render('SdzATIBundle:ati:magasin_sortie.html.twig',array('pm' => $pm,'form' => $form->createView()));
       
        
    }


    public function gestionmagasinAction(Magasin $magasin,$page)
    {

        if ($this->get('security.context')->isGranted('ROLE_EMPLOYE') === false)
        {
            throw new AccessDeniedHttpException("Accès limité - bien vouloir vous authentifier");
            
        }

        if($magasin === null)
        {
            throw $this->createNotFoundException('Magasin[id='.$id.']inexistant.');
        }
        

        $liste_produit = $this->getDoctrine()
                         ->getManager()
                         ->getRepository('SdzATIBundle:ProduitMagasin')
                         ->getProduitMagasin($magasin,5,$page);
                         $nb=ceil(count($liste_produit)/5);

        return $this->render('SdzATIBundle:ati:gestion_magasin.html.twig',array('magasinN' => $magasin,'liste_produit' => $liste_produit,'page' => $page,'nombrePage' =>$nb ));

    }


    public function gestionmagasin_ajouterAction(Magasin $magasin)
    {

        if ($this->get('security.context')->isGranted('ROLE_GESTIONNAIRE') === false)
        {
            throw new AccessDeniedHttpException("Accès limité - Vous ne disposez pas d'autorisation necessaire");
            
        }

        if($magasin === null)
        {
            throw $this->createNotFoundException('Magasin[id='.$id.']inexistant.');
        }
        

        $pm = new ProduitMagasin();
        $form = $this->createForm(new ProduitMagasinAjoutType(),$pm);

        $produitm = $this->getDoctrine()
                         ->getManager()
                         ->getRepository('SdzATIBundle:ProduitMagasin')
                         ->myfindAll($magasin);
        

        $request = $this->get('request');

        if($request->getMethod() == 'POST')
        {
            $form->bind($request);
            if($form->isValid())
            {
                
                $qd = $pm->getQuantite();
                $test=false;
                foreach($produitm as $valeur) 
                {
                    if ($pm->getProduit()->getNom() == $valeur->getProduit()->getNom())
                    {
                        $test=true;
                    }
                    
                }

                if ($test == true)
                {
                    $msg = "Erreur: ce produit existe deja dans le magasin";
                return $this->render('SdzATIBundle:ati:gestion_magasin_ajout.html.twig',array('msg' => $msg,'produitm' => $produitm,'magasin' => $magasin,'pm' => $pm,'form' => $form->createView()));
                }

                $em = $this->getDoctrine()->getManager();
                $pm->setDate(new \Datetime());
                $pm->setMagasin($magasin);

                $produit = $em->getRepository('SdzATIBundle:Produit')->findOneById($pm->getProduit()->getId());
                $produit->setQtet($qd);
                $produit->setQted($qd);

                $entree = new Entree();
                $entree->setUser($this->get('security.context')->getToken()->getUser());
                $entree->setMagasin($magasin);
                $entree->setProduit($produit);
                $entree->setQuantite($pm->getQuantite());
                $entree->setObservation($pm->getObservation());



               
                $statm = new StatMagasin();
                $statm->setDate($pm->getDate());
                $statm->setMois($pm->getDate()->format('m'));
                $statm->setAnnee($pm->getDate()->format('Y'));
                $statm->setEntree($pm->getQuantite());
                $statm->setSortie(0);
                $statm->setFreqvente(0);
                $statm->setMagasin($pm->getMagasin());
                $statm->setProduit($pm->getProduit());

                $stat = $em->getRepository('SdzATIBundle:Statistique')->findStatannee($pm->getProduit()->getId(),$pm->getDate()->format('Y'));

                    if ($stat == null)
                    {
                        $newstat = new Statistique();
                        $newstat->setMois($newstat->getDate()->format('m'));
                        $newstat->setAnnee($newstat->getDate()->format('Y'));
                        $newstat->setEntree($entree->getQuantite());
                        $newstat->setSortie(0);
                        $newstat->setFreqvente(0);
                        $newstat->setProduit($pm->getProduit());

                        $em->persist($entree);
                        $em->persist($produit);
                        $em->persist($pm);
                        $em->persist($newstat);
                        $em->persist($statm);
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('infom', 'Produit bien ajouté');
                        return $this->redirect ($this->generateUrl('sdz_ati_gestion_magasin',array('id' => $magasin->getId())));

                        
                    }

                    $stat2 = $em->getRepository('SdzATIBundle:Statistique')->findStatmois($pm->getProduit()->getId(),$pm->getDate()->format('Y'),$pm->getDate()->format('m'));

                    if ($stat2 === null)
                    {
                        $newstat = new Statistique();
                        $newstat->setMois($newstat->getDate()->format('m'));
                        $newstat->setAnnee($newstat->getDate()->format('Y'));
                        $newstat->setEntree($entree->getQuantite());
                        $newstat->setSortie(0);
                        $newstat->setFreqvente(0);
                        $newstat->setProduit($pm->getProduit());

                        $em->persist($entree);
                        $em->persist($produit);
                        $em->persist($pm);
                        $em->persist($newstat);
                        $em->persist($statm);
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('infom', 'Produit bien ajouté');
                        return $this->redirect ($this->generateUrl('sdz_ati_gestion_magasin',array('id' => $magasin->getId())));

                        
                    }

                    else
                    {
                        $stat2->setEntree($stat2->getEntree() + $entree->getQuantite());


                        $em->persist($entree);
                        $em->persist($produit);
                        $em->persist($pm);
                        $em->persist($stat2);
                        $em->persist($statm);
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('infom', 'Produit bien ajouté');
                        return $this->redirect ($this->generateUrl('sdz_ati_gestion_magasin',array('id' => $magasin->getId())));

                        
                    }
     
            }

        }
        
        

        
        return $this->render('SdzATIBundle:ati:gestion_magasin_ajout.html.twig',array('produitm' => $produitm,'magasin' => $magasin,'pm' => $pm,'form' => $form->createView()));                
    }



    public function userAction($page)
    {
         if ($this->get('security.context')->isGranted('ROLE_EMPLOYE') === false)
        {
            throw new AccessDeniedHttpException("Accès limité - bien vouloir vous authentifier");
            
        }

         $users = $this->getDoctrine()
                         ->getManager()
                         ->getRepository('SdzUserBundle:User')
                         ->getUsers(5,$page);
                         $nb=ceil(count($users)/5);


        return $this->render('SdzATIBundle:ati:personnel.html.twig',array('users' => $users,'page' => $page,'nombrePage' =>$nb ));
    }


    public function user_ajouterAction()
    {
        if ($this->get('security.context')->isGranted('ROLE_ADMIN') === false)
        {
            throw new AccessDeniedHttpException("Accès limité - Vous ne disposez pas d'autorisation necessaire");
            
        }

        $user = new User();

        $form = $this->createForm(new UserType, $user);

        $request = $this->get('request');

        if($request->getMethod() == 'POST')
        {
            $form->bind($request);
            if($form->isValid())
            {
                
                $user->setSalt(md5(time()));
                $encoder = new MessageDigestPasswordEncoder('sha512',true,10);
                $password = $encoder->encodePassword($user->getPassword(),$user->getSalt());
                $user->setPassword($password);
                $em = $this->getDoctrine()->getManager();

                $sameuser = $em->getRepository('SdzUserBundle:User')->findByUsername($user->getUsername());

                if ($sameuser == null)
                {
                    $em->persist($user);
                $em->flush();
                $this->get('session')->getFlashBag()->add('infop', 'Utilisateur bien ajouté');
                return $this->redirect ($this->generateUrl('sdz_ati_personnel_afficher',array('id' => $user->getId())));
                }

                $this->get('session')->getFlashBag()->add('infosame', 'ce username existe deja, bien vouloir choisir un autre');
                
                return $this->render('SdzATIBundle:ati:personnel_erreur.html.twig',array('form' => $form->createView()));
            }

        }
        
        return $this->render('SdzATIBundle:ati:personnel_ajout.html.twig',array('form' => $form->createView()));
    }

    public function user_afficherAction(User $user)
    {
        
       
       if ($this->get('security.context')->isGranted('ROLE_EMPLOYE') === false)
        {
            throw new AccessDeniedHttpException("Accès limité - Bien vouloir vous authentifier");
            
        } 
        if($user === null)
        {
            throw $this->createNotFoundException('User[id='.$id.']inexistant.');
        }
        
        
        return $this->render('SdzATIBundle:ati:personnel_afficher.html.twig',array('user'=>$user));
        
        
    }

    public function user_profilAction(User $user)
    {
        if ($this->get('security.context')->isGranted('ROLE_EMPLOYE') === false)
        {
            throw new AccessDeniedHttpException("Accès limité - Vous ne disposez pas d'autorisation necessaire");
            
        }

        $form = $this->createForm(new UserPersonnalEditType(), $user);
        
        $request = $this->getRequest();
        
        
        $request = $this->get('request');
        
        
       if($request->getMethod() == 'POST')
        {
            $form->bind($request);
            if($form->isValid())
            {
                $user->setSalt(md5(time()));
                $encoder = new MessageDigestPasswordEncoder('sha512',true,10);
                $password = $encoder->encodePassword($user->getPassword(),$user->getSalt());
                $user->setPassword($password);
                $em = $this->getDoctrine()->getManager();

                
                
                    
                    $em->persist($user);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('infop', 'Utilisateur bien modifié');
                    return $this->redirect ($this->generateUrl('sdz_ati_personnel_afficher',array('id' => $user->getId())));
                

                 
                
                
            }

        }
        
        return $this->render('SdzATIBundle:ati:personnel_modifuser.html.twig',array('user'=>$user, 'form' => $form->createView()));  


    }



    public function user_modifierAction(User $user)
    {
       
        if ($this->get('security.context')->isGranted('ROLE_ADMIN') === false)
        {
            throw new AccessDeniedHttpException("Accès limité - Vous ne disposez pas d'autorisation necessaire");
            
        }

        $form = $this->createForm(new UserEditType(), $user);
        
        $request = $this->getRequest();
        
        
        $request = $this->get('request');
        
        
       if($request->getMethod() == 'POST')
        {
            $form->bind($request);
            if($form->isValid())
            {
                
                $em = $this->getDoctrine()->getManager();
                
                    
                    $connection = $em->getConnection();
                $statement = $connection->prepare("DELETE FROM user_role WHERE user_id = :id");
                $statement->bindValue('id', $user->getId());
                $statement->execute();
                


                
                $em->persist($user);
                $em->flush();
                $this->get('session')->getFlashBag()->add('infop', 'Utilisateur bien modifié');
                return $this->redirect ($this->generateUrl('sdz_ati_personnel_afficher',array('id' => $user->getId())));
                
                
            }

        }
        
        return $this->render('SdzATIBundle:ati:personnel_modif.html.twig',array('form' => $form->createView()));  
    }

    public function user_supprimerAction(User $user)
    {
        
        if ($this->get('security.context')->isGranted('ROLE_ADMIN') === false)
        {
            throw new AccessDeniedHttpException("Accès limité - Vous ne disposez pas d'autorisation necessaire");
            
        }

        $form = $this->createFormBuilder()->getForm();
    
        
        $request = $this->getRequest();
        
        
        if ($request->getMethod() == 'POST')
        {
            $form->bind($request);
            if ($form->isValid())
            {
                // On supprime 
                $em = $this->getDoctrine()->getManager();

                $entree = $em->getRepository('SdzATIBundle:Entree')->findByUser($user);
                $sortie = $em->getRepository('SdzATIBundle:Sortie')->findByUser($user);

                if ($entree != null)
                {
                    
                    $erreur = "Cet Utilisateur ne peut être supprimé car ayant éffectué des opérations enregistrées dans la base de donnée";


                    return $this->render('SdzATIBundle:ati:personnel_suppr_erreur.html.twig',array('erreur'=>$erreur));
                }

                if ($sortie != null)
                {
                    $erreur = "Cet Utilisateur ne peut être supprimé \ncar ayant éffectué des opérations enregistrées dans la base de donnée";


                    return $this->render('SdzATIBundle:ati:personnel_suppr_erreur.html.twig',array('erreur'=>$erreur));
                }

                

                $connection = $em->getConnection();
                $statement = $connection->prepare("DELETE FROM user_role WHERE user_id = :id");
                $statement->bindValue('id', $user->getId());
                $statement->execute();

                $em->remove($user);
                $em->flush();
                
               
                
                return $this->redirect($this->generateUrl('sdz_ati_personnel'));
            }
        }
        
        return $this->render('SdzATIBundle:ati:personnel_suppr.html.twig',array('user' => $user,'form' => $form->createView()));
        
    }


    
    public function produitAction($page)
    {
        
        if ($this->get('security.context')->isGranted('ROLE_EMPLOYE') === false)
        {
            throw new AccessDeniedHttpException("Accès limité - Bien vouloir vous authentifier");
            
        }

        $produits = $this->getDoctrine()
                         ->getManager()
                         ->getRepository('SdzATIBundle:Produit')
                         ->getProduits(10,$page);
                         $nb=ceil(count($produits)/10);
        return $this->render('SdzATIBundle:ati:produit.html.twig',array('produits' => $produits,'page' => $page,'nombrePage' => $nb));
    }

    public function produit_ajouterAction()
    {
        if ($this->get('security.context')->isGranted('ROLE_ADMIN') === false)
        {
            throw new AccessDeniedHttpException("Accès limité - Vous ne disposez pas d'autorisation necessaire");
            
        }

        $produit = new Produit();

        $form = $this->createForm(new ProduitType, $produit);

        $request = $this->get('request');

        if($request->getMethod() == 'POST')
        {
            $form->bind($request);
            if($form->isValid())
            {
                $produit->setDate(new \Datetime());

                $em = $this->getDoctrine()->getManager();
                $em->persist($produit);
                $em->flush();
                $this->get('session')->getFlashBag()->add('infopr', 'Produit bien ajouté');
                return $this->redirect ($this->generateUrl('sdz_ati_produit_afficher',array('id' => $produit->getId())));
            }

        }
        
        return $this->render('SdzATIBundle:ati:produit_ajout.html.twig',array('form' => $form->createView()));
    }

    
    public function produit_afficherAction(Produit $produit)
    {
        
        

        if($produit === null)
        {
            throw $this->createNotFoundException('Produit[id='.$id.']inexistant.');
        }
        
        
        return $this->render('SdzATIBundle:ati:produit_afficher.html.twig',array('produit'=>$produit));
        
        
    }



    public function produit_modifierAction(Produit $produit)
    {
       
       if ($this->get('security.context')->isGranted('ROLE_ADMIN') === false)
        {
            throw new AccessDeniedHttpException("Accès limité - Vous ne disposez pas d'autorisation necessaire");
            
        }

       $form = $this->createForm(new ProduitEditType(), $produit);
        
        $request = $this->getRequest();
        
        
        $request = $this->get('request');
        
        
       if($request->getMethod() == 'POST')
        {
            $form->bind($request);
            if($form->isValid())
            {
                
                $em = $this->getDoctrine()->getManager();
                $em->persist($produit);
                $em->flush();
                $this->get('session')->getFlashBag()->add('infopr', 'Produit bien modifié');
                return $this->redirect ($this->generateUrl('sdz_ati_produit_afficher',array('id' => $produit->getId())));
            }

        }
        
        return $this->render('SdzATIBundle:ati:produit_modif.html.twig',array('form' => $form->createView()));  
    }

    public function produit_supprimerAction(Produit $produit)
    {
        
        if ($this->get('security.context')->isGranted('ROLE_ADMIN') === false)
        {
            throw new AccessDeniedHttpException("Accès limité - Vous ne disposez pas d'autorisation necessaire");
            
        }

        $form = $this->createFormBuilder()->getForm();
        
        $request = $this->getRequest();
        
        
        if ($request->getMethod() == 'POST')
        {
            $form->bind($request);
            if ($form->isValid())
            {
                // On supprime l'article
                $em = $this->getDoctrine()->getManager();
                $em->remove($produit);
                $em->flush();
                
                
                
                return $this->redirect($this->generateUrl('sdz_ati_produit'));
            }
        }
        
        return $this->render('SdzATIBundle:ati:produit_suppr.html.twig',array('produit' => $produit,'form' => $form->createView()));
        
    }



     public function magasin_ajouterAction()
    {
        if ($this->get('security.context')->isGranted('ROLE_ADMIN') === false)
        {
            throw new AccessDeniedHttpException("Accès limité - Vous ne disposez pas d'autorisation necessaire");
            
        }

        $magasin = new Magasin();

        $form = $this->createForm(new MagasinType, $magasin);

        $request = $this->get('request');

        if($request->getMethod() == 'POST')
        {
            $form->bind($request);
            if($form->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                $em->persist($magasin);
                $em->flush();
                $this->get('session')->getFlashBag()->add('infoma', 'Magasin bien ajouté');
                return $this->redirect ($this->generateUrl('sdz_ati_magasin_afficher',array('id' => $magasin->getId())));
            }

        }
        
        return $this->render('SdzATIBundle:ati:magasin_ajout.html.twig',array('form' => $form->createView()));
    }


    public function magasin_modifierAction(Magasin $magasin)
    {
       
        if ($this->get('security.context')->isGranted('ROLE_ADMIN') === false)
        {
            throw new AccessDeniedHttpException("Accès limité - Vous ne disposez pas d'autorisation necessaire");
            
        }

        $form = $this->createForm(new MagasinEditType(), $magasin);
        
        $request = $this->getRequest();
        
        
        $request = $this->get('request');
        
        
       if($request->getMethod() == 'POST')
        {
            $form->bind($request);
            if($form->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                $em->persist($magasin);
                $em->flush();
                $this->get('session')->getFlashBag()->add('infoma', 'Magasin bien modifié');
                return $this->redirect ($this->generateUrl('sdz_ati_magasin_afficher',array('id' => $magasin->getId())));
            }

        }
        
        return $this->render('SdzATIBundle:ati:magasin_modif.html.twig',array('form' => $form->createView()));  
    }


    public function statistiqueAction()
    {
        if ($this->get('security.context')->isGranted('ROLE_EMPLOYE') === false)
        {
            throw new AccessDeniedHttpException("Accès limité - Bien vouloir vous authentifier");
            
        }

        $datenow = new \Datetime();
        $mois = $datenow->format('m');




        
        
        


        return $this->render('SdzATIBundle:ati:statistique.html.twig',array('format' => $mois));
    }

    public function statistique_mensuelleAction()
    {
        if ($this->get('security.context')->isGranted('ROLE_EMPLOYE') === false)
        {
            throw new AccessDeniedHttpException("Accès limité - Bien vouloir vous authentifier");
            
        }

        $em = $this->getDoctrine()->getManager();
        $date = new \Datetime();
        $moisn = $date->format('m');
        $mois = $date->format('F');
        $annee = $date->format('Y');

        $stats = $em->getRepository('SdzATIBundle:Statistique')->findStatcourant($date->format('m'),$date->format('Y'));


        return $this->render('SdzATIBundle:ati:statistique_mensuelle.html.twig',array('moisn' => $moisn,'stats' => $stats, 'mois' => $mois, 'annee'=>$annee));
    }

    public function statistique_annuelleAction()
    {
        if ($this->get('security.context')->isGranted('ROLE_EMPLOYE') === false)
        {
            throw new AccessDeniedHttpException("Accès limité - Bien vouloir vous authentifier");
            
        }

        $em = $this->getDoctrine()->getManager();
        $date = new \Datetime();
        $mois = $date->format('F');
        $annee = $date->format('Y');



        $annees = $em->getRepository('SdzATIBundle:Statistique')->findStatAllyear();


        return $this->render('SdzATIBundle:ati:statistique_annuelle.html.twig',array('annees' => $annees));
    }

    public function statistique_annuelle_anneeAction($annee)
    {
         if ($this->get('security.context')->isGranted('ROLE_EMPLOYE') === false)
        {
            throw new AccessDeniedHttpException("Accès limité - Bien vouloir vous authentifier");
            
        }

        $em = $this->getDoctrine()->getManager();
        $mois = $em->getRepository('SdzATIBundle:Statistique')->findStatAllmonth($annee);

        if ($mois == null) 
        {
         throw $this->createNotFoundException('Page Introuvable');
        }

         return $this->render('SdzATIBundle:ati:statistique_annuelle_annee.html.twig',array('mois' => $mois,'annee'=>$annee));

    }

    public function statistique_moisAction($annee,$mois)
    {
         if ($this->get('security.context')->isGranted('ROLE_EMPLOYE') === false)
        {
            throw new AccessDeniedHttpException("Accès limité - Bien vouloir vous authentifier");
            
        }

        $em = $this->getDoctrine()->getManager();
        $stats = $em->getRepository('SdzATIBundle:Statistique')->findStatcourant($mois,$annee);

        if ($stats == null) 
        {
         throw $this->createNotFoundException('Page Introuvable');
        }
        return $this->render('SdzATIBundle:ati:statistique_mois.html.twig',array('mois' => $mois,'annee'=>$annee,'stats'=>$stats));


    }



    public function testAction($name)
    {
        

        //on stocke la vue à convertir en PDF, en n'oubliant pas les paramètres twig si la vue comporte des données dynamiques
        
        $date = new \Datetime();
        $html = $this->renderView('SdzATIBundle:ati:pdf.html.twig', array('name' => $name,'date'=>$date));
        //on instancie la classe Html2Pdf_Html2Pdf en lui passant en paramètre
        //le sens de la page "portrait" => p ou "paysage" => l
        //le format A4,A5...
        //la langue du document fr,en,it...
        $html2pdf = $this->get('html2pdf_factory')->create('P','A4','fr', false, 'ISO-8859-15', array(10, 10, 10, 5));
       
 
        //SetDisplayMode définit la manière dont le document PDF va être affiché par l’utilisateur
        //fullpage : affiche la page entière sur l'écran
        //fullwidth : utilise la largeur maximum de la fenêtre
        //real : utilise la taille réelle
        $html2pdf->pdf->SetDisplayMode('fullpage');
 
        //writeHTML va tout simplement prendre la vue stocker dans la variable $html pour la convertir en format PDF
        $html2pdf->writeHTML($html,isset($_GET['vuehtml']));
 
        return new Response($html2pdf->Output('nom-du-pdf.pdf'), 200, array('Content-Type' => 'application/pdf'));
    }

    public function imprimer_moisAction($annee,$mois)
    {
        

        //on stocke la vue à convertir en PDF, en n'oubliant pas les paramètres twig si la vue comporte des données dynamiques
        
        
        if ($this->get('security.context')->isGranted('ROLE_EMPLOYE') === false)
        {
            throw new AccessDeniedHttpException("Accès limité - Bien vouloir vous authentifier");
            
        }

        $date = new \Datetime();
        $em = $this->getDoctrine()->getManager();
        $stats = $em->getRepository('SdzATIBundle:Statistique')->findStatcourant($mois,$annee);

        if ($stats == null) 
        {
         throw $this->createNotFoundException('Page Introuvable');
        }
        $liste = [1=>'Janvier',2=>'Fevrier',3=>'Mars',4=>'Avril',5=>'Mai',6=>'Juin',7=>'Juillet',8=>'Aout',9=>'Septembre',10=>'Octobre',11=>'Novembre',12=>'Decembre'];
        foreach ($liste as $key => $value) 
        {
            if ($mois == $key)
            {
                $mois=$value;
            }
        }

        $html = $this->renderView('SdzATIBundle:ati:pdf.html.twig', array('mois' => $mois,'annee'=>$annee, 'date'=>$date,'stats'=>$stats));
        
        //on instancie la classe Html2Pdf_Html2Pdf en lui passant en paramètre
        //le sens de la page "portrait" => p ou "paysage" => l
        //le format A4,A5...
        //la langue du document fr,en,it...
        $html2pdf = $this->get('html2pdf_factory')->create('P','A4','fr', false, 'ISO-8859-15', array(10, 10, 10, 5));
       
 
        //SetDisplayMode définit la manière dont le document PDF va être affiché par l’utilisateur
        //fullpage : affiche la page entière sur l'écran
        //fullwidth : utilise la largeur maximum de la fenêtre
        //real : utilise la taille réelle
        $html2pdf->pdf->SetDisplayMode('fullpage');
 
        //writeHTML va tout simplement prendre la vue stocker dans la variable $html pour la convertir en format PDF
        $html2pdf->writeHTML($html,isset($_GET['vuehtml']));
 
        return new Response($html2pdf->Output('nom-du-pdf.pdf'), 200, array('Content-Type' => 'application/pdf'));
    }

public function imprimer_etatAction()
    {
        

        //on stocke la vue à convertir en PDF, en n'oubliant pas les paramètres twig si la vue comporte des données dynamiques
        
        
        if ($this->get('security.context')->isGranted('ROLE_EMPLOYE') === false)
        {
            throw new AccessDeniedHttpException("Accès limité - Bien vouloir vous authentifier");
            
        }

        $date = new \Datetime();
        $em = $this->getDoctrine()->getManager();
        $produits = $em->getRepository('SdzATIBundle:Produit')->findAll();

        $html = $this->renderView('SdzATIBundle:ati:pdf_etat.html.twig', array('date'=>$date,'produits'=>$produits));
        
        //on instancie la classe Html2Pdf_Html2Pdf en lui passant en paramètre
        //le sens de la page "portrait" => p ou "paysage" => l
        //le format A4,A5...
        //la langue du document fr,en,it...
        $html2pdf = $this->get('html2pdf_factory')->create('P','A4','fr', false, 'ISO-8859-15', array(10, 10, 10, 5));
       
 
        //SetDisplayMode définit la manière dont le document PDF va être affiché par l’utilisateur
        //fullpage : affiche la page entière sur l'écran
        //fullwidth : utilise la largeur maximum de la fenêtre
        //real : utilise la taille réelle
        $html2pdf->pdf->SetDisplayMode('fullpage');
 
        //writeHTML va tout simplement prendre la vue stocker dans la variable $html pour la convertir en format PDF
        $html2pdf->writeHTML($html,isset($_GET['vuehtml']));
 
        return new Response($html2pdf->Output('etat_des_stocks.pdf'), 200, array('Content-Type' => 'application/pdf'));
    }


   
}
