<?php

namespace PollMe\Controller;

use Rock\Http\Request;
use Rock\Http\Exception\HttpException;

use PollMe\Entity\Comment;
use PollMe\Entity\Response;
use PollMe\Entity\Survey;


class SurveysController extends BaseController
{
    public function newAction()
    {
        $this->requireUser();

        return $this->render('surveys/new.html.twig');
    }

    public function listAction()
    {
        $survey_repository = $this->container['repository.survey'];
        $surveys = $survey_repository->findAll();

        foreach ($surveys as $survey) {
            $survey->computePercentages();
        }

        return $this->render('surveys/list.html.twig', array(
            'surveys' => $surveys,
        ));
    }

    public function listMineAction()
    {
        $this->requireUser();

        $survey_repository = $this->container['repository.survey'];
        $surveys = $survey_repository->findByOwnerId($this->getUser()->getId());

        foreach ($surveys as $survey) {
            $survey->computePercentages();
        }

        return $this->render('surveys/list_mine.html.twig', array(
            'surveys' => $surveys,
        ));
    }

    public function searchAction(Request $request)
    {
        $search = $request->request->get('keyword');

        if (empty($search)) {
            $request->getSession()->getFlashBag()->add('error', 'Une recherche vide ? Mais pourquoi ?!');
            $this->redirect('/');
        }

        $survey_repository = $this->container['repository.survey'];
        $surveys = $survey_repository->findBySearch($search);

        foreach ($surveys as $survey) {
            $survey->computePercentages();
        }

        return $this->render('surveys/search.html.twig', array(
            'surveys' => $surveys,
            'search'  => $search,
        ));
    }

    public function voteAction(Request $request, $survey_id)
    {
        $response_repository = $this->container['repository.response'];
        $response = $response_repository->findById((int) $request->request->get('responseId'));

        if ($response === null) {
            throw $this->createNotFoundException('Réponse inconnue');
        }

        $response->setCount($response->getCount() + 1);
        $response_repository->persist($response);

        $request->getSession()->getFlashBag()->add('info', 'A voté !');
        $this->redirect('/');
    }

    public function commentAction(Request $request, $survey_id)
    {
        $this->requireUser();

        $errors = array();
        $comment = new Comment();

        $survey_repository = $this->container['repository.survey'];
        $survey = $survey_repository->findById((int) $survey_id);

        if ($survey === null) {
            throw $this->createNotFoundException('Sondage inconnu');
        }

        try {
            $comment_text = $this->validateComment($request->request->get('comment'));
            $comment->setComment($comment_text);
        } catch (\Exception $e) {
            $errors[] = $e->getMessage();
        }

        if (count($errors) === 0) {
            $comment->setSurveyId($survey_id);
            $comment->setUserId($this->getUser()->getId());
            $comment->setCreatedAt('now');

            $repository = $this->container['repository.comment'];
            $repository->persist($comment);

            $request->getSession()->getFlashBag()->add('notice', 'Réaction ajoutée !');
            $this->redirect('/');
        } else {
            $surveys = $survey_repository->findAll();

            foreach ($surveys as $survey) {
                $survey->computePercentages();
            }

            return $this->render('surveys/list.html.twig', array(
                'errors'  => $errors,
                'surveys' => $surveys,
            ));
        }
    }

    public function deleteAction($survey_id)
    {
        $this->requireUser();

        $survey_repository = $this->container['repository.survey'];
        $survey = $survey_repository->findById((int) $survey_id);

        if ($survey === null) {
            throw $this->createNotFoundException('Sondage inconnu');
        }

        if ($survey->getOwnerId() !== $this->getUser()->getId()) {
            throw new HttpException(403, 'Il n\'est possible de supprimer que ses propres sondages.');
        }

        $survey_repository->delete($survey);

        $this->request->getSession()->getFlashBag()->add('info', 'Sondage supprimé.');
        $this->redirect('/');
    }

    public function createAction(Request $request)
    {
        $this->requireUser();

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

        if (count($survey->getResponses()) === 0) {
            $errors[] = 'Un sondage sans question n\'est pas très utile ...';
        }

        if (count($survey->getResponses()) < 2) {
            $errors[] = 'Il faut saisir au moins deux réponses.';
        }

        if (count($errors) === 0) {
            $survey_repository = $this->container['repository.survey'];
            $survey_repository->persist($survey);

            $request->getSession()->getFlashBag()->add('notice', 'Sondage créé.');
            $this->redirect('/surveys/mine');
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

    protected function validateComment($comment)
    {
        if (empty($comment)) {
            throw new \Exception('Un commentaire vide n\'est pas très constructif.');
        }

        return $comment;
    }
}
