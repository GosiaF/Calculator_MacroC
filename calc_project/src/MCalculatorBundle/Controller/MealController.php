<?php

namespace MCalculatorBundle\Controller;

use MCalculatorBundle\Entity\Meal;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\VarDumper\Exception\ThrowingCasterException;

/**
 * Meal controller.
 *
 * @Route("meal")
 */
class MealController extends Controller
{

    /**
     * Lists all meal entities.
     *
     * @Route("/compose", name="compose_meal")
     */
    public function composeMealAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $ingRepo = $em->getRepository('MCalculatorBundle:Ingredient');

        $selectedIngredientId = $request->get('selectedIngredientId');
        $ingredients = $ingRepo->findAll();

        $selectWeight = $request->get('selectWeight');
        $submit = $request->get('submit');
        $historicalIngredients = $request->get('historicalIngredients', []);

        if ($historicalIngredients && !empty($historicalIngredients)) {
            $ingredientsArr = $ingRepo->findBy(['id' => $historicalIngredients]);
        } else {
            $ingredientsArr = [];
        }

        if ($selectedIngredientId) {
            $singleIngredient = $ingRepo->find($selectedIngredientId);
            if ($submit !== 'done') {
                $ingredientsArr[] = $singleIngredient;
            }
        } else {
            $singleIngredient = null;
        }


        $historicalWeight = $request->get('historicalWeight', []);
        if ($selectWeight) {
            $historicalWeight[] = $selectWeight;
        }

        foreach ($ingredients as $key => $ingredient) {
            if (in_array($ingredient->getId(), $historicalIngredients) || $ingredient->getId() == $selectedIngredientId) {
                unset($ingredients[$key]);
            }
        }

        $i = 0;
        $totalKcal = 0;
        $totalFat = 0;
        $totalCarbohydrates = 0;
        $totalProtein = 0;

        foreach ($ingredientsArr as $ingredient) {

            $ingrKcal = $ingredient->getKcal();
            $ingrFat = $ingredient->getFat();
            $ingrCarbohydrates = $ingredient->getCarbohydrates();
            $ingrProtein = $ingredient->getProtein();

            $kcal = $ingrKcal * $historicalWeight[$i];
            $totalKcal = $totalKcal + $kcal / 100;

            $fat = $ingrFat * $historicalWeight[$i];
            $totalFat = $totalFat + $fat / 100;

            $carbohydrates = $ingrCarbohydrates * $historicalWeight[$i];
            $totalCarbohydrates = $totalCarbohydrates + $carbohydrates / 100;

            $protein = $ingrProtein * $historicalWeight[$i];
            $totalProtein = $totalProtein + $protein / 100;

            $i = $i + 1;
        }


        if ($submit === 'done') {
            return $this->render('@MCalculator/Default/done.html.twig', array(
                'totalKcal' => $totalKcal,
                'totalFat' => $totalFat,
                'totalCarbohydrates' => $totalCarbohydrates,
                'totalProtein' => $totalProtein
            ));
        }

        return $this->render('@MCalculator/Default/choose.html.twig', array(
            'ingredients' => $ingredients,
            'singleIngredient' => $singleIngredient,
            'selectWeight' => $selectWeight,
            'ingredientsArr' => $ingredientsArr,
            'weightArr' => $historicalWeight,
            'totalKcal' => $totalKcal,
            'totalFat' => $totalFat,
            'totalCarbohydrates' => $totalCarbohydrates,
            'totalProtein' => $totalProtein


        ));
    }


    /**
     * Lists all meal entities.
     *
     * @Route("/", name="meal_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $meals = $em->getRepository('MCalculatorBundle:Meal')->findAll();

        return $this->render('meal/index.html.twig', array(
            'meals' => $meals,
        ));
    }

    /**
     * Creates a new meal entity.
     *
     * @Route("/new", name="meal_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $meal = new Meal();
        $form = $this->createForm('MCalculatorBundle\Form\MealType', $meal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($meal);
            $em->flush();

            return $this->redirectToRoute('meal_show', array('id' => $meal->getId()));
        }

        return $this->render('meal/new.html.twig', array(
            'meal' => $meal,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a meal entity.
     *
     * @Route("/{id}", name="meal_show")
     * @Method("GET")
     */
    public function showAction(Meal $meal)
    {
        $deleteForm = $this->createDeleteForm($meal);

        return $this->render('meal/show.html.twig', array(
            'meal' => $meal,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing meal entity.
     *
     * @Route("/{id}/edit", name="meal_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Meal $meal)
    {
        $deleteForm = $this->createDeleteForm($meal);
        $editForm = $this->createForm('MCalculatorBundle\Form\MealType', $meal);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('meal_edit', array('id' => $meal->getId()));
        }

        return $this->render('meal/edit.html.twig', array(
            'meal' => $meal,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a meal entity.
     *
     * @Route("/{id}", name="meal_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Meal $meal)
    {
        $form = $this->createDeleteForm($meal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($meal);
            $em->flush();
        }

        return $this->redirectToRoute('meal_index');
    }

    /**
     * Creates a form to delete a meal entity.
     *
     * @param Meal $meal The meal entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Meal $meal)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('meal_delete', array('id' => $meal->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
