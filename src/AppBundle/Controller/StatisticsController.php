<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Promo;
use AppBundle\Entity\Participation;
use AppBundle\Form\PromoType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Ob\HighchartsBundle\Highcharts\Highchart;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * Class PromoController
 * @package AppBundle\Controller
 * @Route("/promo/{slug}/statistics")
 */
class StatisticsController extends Controller
{
    /**
     * @Route("", name="Promo_statistics")
     * @Template("AppBundle::Promo/statistics.html.twig")
     */
    public function indexAction(Promo $promo, Request $request)
    {
        if ((!$request->query->has('type'))or($request->query->get('type')=='eleves')) {
            $res = $this->getDoctrine()->getRepository('AppBundle:Participation')->findElevesPerOrganization($promo->getSlug());
            $ob = new Highchart();
            $ob->chart->renderTo('chart');
            $ob->title->text('Participating eleves by institution');
            $ob->plotOptions->pie(array(
                'allowPointSelect' => true,
                'cursor' => 'pointer',
                'dataLabels' => array('enabled' => true),
                'showInLegend' => false,
                'exporting' => array('enabled' => false) 
            ));
            $data = array();
            foreach ($res as $p) {
                $data[] = array($p['organization'], (int)$p['nb']);
            }
            $ob->series(array(array('type' => 'pie', 'name' => 'Eleves', 'data' => $data)));
            return array('chart' => $ob, 'promo' => $promo);
        }elseif ($request->query->get('type')=='flags')
            return $this->flagsAction($promo);
        elseif ($request->query->get('type')=='activity') {
            return $this->activityAction($promo);
        }elseif ($request->query->get('type')=='rate') {
            return $this->successRate($promo);
        }
        else throw new NotFoundHttpException();
    }

    public function flagsAction(Promo $promo){
        $flags = $this->getDoctrine()->getRepository('AppBundle:Submission')->findFlagsSubmissions($promo->getId());
        $ob = new Highchart();
        $ob->chart->renderTo('chart');
        $ob->title->text('Browser market shares at a specific website in 2010');
        $ob->plotOptions->pie(array(
            'allowPointSelect'  => true,
            'cursor'    => 'pointer',
            'dataLabels'    => array('enabled' => false),
            'showInLegend'  => true
        ));
        $ob->xAxis->title(array('text'  => "Flags"));
        $ob->yAxis->title(array('text'  => "Time"));
        $data = array();
        foreach ($flags as $f){
            $data[]=array($f['projet'],5);
        };
        $ob->series(array(array('type' => 'bar','name' => 'Browser share', 'data' => $data)));
        return array('promo'=>$promo,'chart'=>$ob);
    }
    public function activityAction(Promo $promo){
        $submissions = $this->getDoctrine()->getRepository('AppBundle:Submission')->findTrafficSubmissions($promo->getId());
        $counts = array();
        $dates = array();
        $flags = array();
        foreach($submissions as $submission){
            $counts[]=(int)$submission['nb'];
            $dates[]=$submission['date'];
            $flags[]=(int)$submission['nbr'];
        }
        $sellsHistory = array(
            array(
                "name" => "Tasks submissions",
                "data" => $counts
            ),
            array(
                "name" => "Valid submissions",
                "data" => $flags,
                "color" => "green"
            )
        );
        $ob = new Highchart();
        $ob->chart->renderTo('chart');
        $startDate = time();
        $interval = date('Y-m-d', strtotime('-6 day', $startDate));
        $ob->title->text("Tasks submissions between $interval and ".date('Y-m-d'));
        $ob->yAxis->min(0);
        $ob->yAxis->title(array('text' => "Number of submissions"));
        $ob->xAxis->title(array('text'  => "Day"));
        $ob->xAxis->categories($dates);
        $ob->series($sellsHistory);
        return array('promo'=>$promo,'chart'=>$ob);

    }

    public function successRate(Promo $promo){
        $rates = $this->getDoctrine()->getRepository('AppBundle:Projet')->findSuccessRateForTasksByPromo($promo->getId());
        $ratios = array();
        $tasks = array();
        foreach($rates as $rate){
            $tasks[]=$rate->getName();
            $countSubmissions = 0;
            $countFlags = 0;
            foreach($rate->getSubmissions() as $submission){
                $countSubmissions++;
                if ($submission->getResponse())
                    $countFlags++;
            }
            if ($countSubmissions==0)
                $ratios[]=0;
            else $ratios[]=ceil(($countFlags/$countSubmissions)*100);
        }
        $sellsHistory = array(
            array(
                "name" => "Success rate",
                "data" => $ratios
            )
        );
        $ob = new Highchart();
        $ob->chart->renderTo('chart');
        $ob->title->text("Success rate by projet");
        $ob->chart->type('column');
        $ob->yAxis->max(100);
        $ob->yAxis->min(0);
        $ob->yAxis->title(array('text' => "Success rate (%)"));
        $ob->title->text('Success rate by projet');
        $ob->xAxis->title(array('text' => "projet name"));
        $ob->xAxis->categories($tasks);
        $ob->series($sellsHistory);
        return array('promo'=>$promo,'chart'=>$ob);
    }
}
