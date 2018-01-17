<?php

namespace AppBundle\Controller;

use AppBundle\Form\CategoryType;
use AppBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
/**
 * Class CategoryController
 * @package AppBundle\Controller
 *
 * @Route("/category")
 * @Security("is_granted('ROLE_ADMIN')")
 */
class CategoryController extends Controller
{
    /**
     * @Route("/", name="category_list")
     * @Template()
     */
    public function indexAction()
    {
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();
        return array(
            'categories' => $categories,
        );
    }

    /**
     * @Route("/new", name="category_new")
     * @Template()
     */
    public function newAction(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(new CategoryType(), $category);
        $form->add('add', 'submit');
        $form->handleRequest($request);

        if($form->isValid()){
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('category_list');
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/{slug}/edit", name="category_edit")
     * @Template()
     */
    public function editAction(Category $category, Request $request)
    {
        $form = $this->createForm(new CategoryType(), $category);
        $form->add('submit your category', 'submit');
        $form->handleRequest($request);

        if($form->isValid()){
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('category_list');
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/{slug}/delete", name="category_delete")
     * @Template()
     */
    public function deleteAction(Category $category)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($category);
        $em->flush();
        return $this->redirectToRoute('category_list');
    }

}
