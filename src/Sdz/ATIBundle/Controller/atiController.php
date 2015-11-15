<?php

namespace Sdz\ATIBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sdz\ATIBundle\Entity\Produit;
use Sdz\ATIBundle\Form\ProduitType;
use Sdz\ATIBundle\Entity\Magasin;
use Sdz\ATIBundle\Form\MagasinType;
use Ob\HighchartsBundle\Highcharts\Highchart;

class atiController extends Controller
{
    public function indexAction()
    {
        
    // Chart
    $series = array( array("name" => "Data Serie Name",    "data" => array(1,2,4,5,6,3,8)));

    $ob = new Highchart();
    $ob->chart->renderTo('linechart');  // The #id of the div where to render the chart
    $ob->title->text('Chart Title');
    $ob->xAxis->title(array('text'  => "Horizontal axis title"));
    $ob->yAxis->title(array('text'  => "Vertical axis title"));
    $ob->series($series);

    return $this->render('test.html.twig', array('chart' => $ob));

        
    }

    public function testAction($name)
    {
        //on stocke la vue à convertir en PDF, en n'oubliant pas les paramètres twig si la vue comporte des données dynamiques
        $html = $this->renderView('SdzATIBundle:ati:pdf.html.twig', array('name' => $name));
         
        //on instancie la classe Html2Pdf_Html2Pdf en lui passant en paramètre
        //le sens de la page "portrait" => p ou "paysage" => l
        //le format A4,A5...
        //la langue du document fr,en,it...
        $html2pdf = new \Html2Pdf_Html2Pdf('P','A4','fr');
 
        //SetDisplayMode définit la manière dont le document PDF va être affiché par l’utilisateur
        //fullpage : affiche la page entière sur l'écran
        //fullwidth : utilise la largeur maximum de la fenêtre
        //real : utilise la taille réelle
        $html2pdf->pdf->SetDisplayMode('real');
 
        //writeHTML va tout simplement prendre la vue stocker dans la variable $html pour la convertir en format PDF
        $html2pdf->writeHTML($html);
 
        //Output envoit le document PDF au navigateur internet avec un nom spécifique qui aura un rapport avec le contenu à convertir (exemple : Facture, Règlement…)
        $html2pdf->Output('Facture.pdf');
         
     
        return new Response();
    }

    


 }
