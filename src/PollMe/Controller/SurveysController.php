<?php

namespace PollMe\Controller;

use Rock\Core\Controller\Controller;
use Rock\Http\Request;

use PollMe\Entity\Response;
use PollMe\Entity\Survey;


class SurveysController extends Controller
{
    public function newAction(Request $request)
    {
        return $this->render('surveys/new.html.twig');
    }

    public function createAction(Request $request)
    {
        $errors = array();
        $survey = new Survey();
        $survey->setOwnerId($this->getUser()->getId());

        try {
            $question = $this->validateQuestion($request->request->get('questionSurvey'));
            $survey->setQuestion($question);
        } catch (\Exception $e) {
            $errors[] = $e->getMessage();
        }

        for ($i = 1; $i <= 5; $i += 1) {
            try {
                $response = $this->validateResponse($request->request->get('responseSurvey'.$i), $i);
                if ($response !== null) {
                    $survey->addResponse($response);
                }
            } catch (\Exception $e) {
                $errors[] = $e->getMessage();
            }
        }

        if (count($errors) === 0) {
            $survey_repository = $this->container['repository.survey'];
            $survey_repository->persist($survey);

            $request->getSession()->getFlashBag()->add('notice', 'Sondage créé.');
            $this->redirect('/');
        } else {
            return $this->render('surveys/new.html.twig', array(
                'errors' => $errors
            ));
        }
    }

    protected function validateQuestion($question)
    {
        if (empty($question)) {
            throw new \Exception('La question est obligatoire.');
        }

        if (strlen($question) > 255) {
            throw new \Exception('La question est trop longue.');
        }

        return $question;
    }

    protected function validateResponse($response, $i)
    {
        if (empty($response)) {
            return null;
        }

        if (strlen($response) > 255) {
            throw new \Exception('La réponse n°'.$i.' est trop longue.');
        }

        return new Response(array('title' => $response));
    }
}
