<?php

namespace PollMe\Controller;

use Rock\Http\Request;

use PollMe\Entity\User;


class RegistrationController extends BaseController
{
    public function registerAction()
    {
        return $this->render('register.html.twig');
    }

    public function submitRegisterAction(Request $request)
    {
        $errors = array();
        $user = new User();

        try {
            $username = $this->validateUsername($request->request->get('signUpLogin'));
            $user->setNickname($username);
        } catch (\Exception $e) {
            $errors[] = $e->getMessage();
        }

        try {
            $mail = $this->validateMail($request->request->get('signUpMail'));
            $user->setMail($mail);
        } catch (\Exception $e) {
            $errors[] = $e->getMessage();
        }

        try {
            $password = $this->validatePasswords($request->request->get('signUpPassword'), $request->request->get('signUpPassword2'));
            $user->setPassword($password);
        } catch (\Exception $e) {
            $errors[] = $e->getMessage();
        }

        if (count($errors) === 0) {
            $user_repository = $this->container['repository.user'];
            $user_repository->persist($user);

            $request->attributes->set('_user', $user);
            $request->getSession()->set('user_id', $user->getId());

            $request->getSession()->getFlashBag()->add('notice', 'Vous êtes désormais inscrit !');
            $this->redirect('/');
        } else {
            return $this->render('register.html.twig', array(
                'errors' => $errors
            ));
        }
    }

    protected function validateUsername($username)
    {
        if (empty($username)) {
            throw new \Exception('Le pseudo est obligatoire.');
        }

        $len = strlen($username);
        if ($len < 3 || $len > 10) {
            throw new \Exception('La longueur du pseudo doit être comprise entre 3 et 10 caractères.');
        }

        if (preg_match('`^[a-zA-Z]+$`', $username) === 0) {
            throw new \Exception('Le pseudo doit être composé uniquement de lettres.');
        }

        $user_repository = $this->container['repository.user'];
        if (!$user_repository->isNicknameAvailable($username)) {
            throw new \Exception('Ce pseudo est déjà utilisé.');
        }

        return $username;
    }

    protected function validateMail($mail)
    {
        if (empty($mail)) {
            throw new \Exception('Le mail est obligatoire.');
        }

        if (($mail = filter_var($mail, FILTER_VALIDATE_EMAIL)) === false) {
            throw new \Exception('Le mail est invalide.');
        }

        $user_repository = $this->container['repository.user'];
        if (!$user_repository->isMailAvailable($mail)) {
            throw new \Exception('Ce mail est déjà utilisé.');
        }

        return $mail;
    }

    protected function validatePasswords($password, $confirmation)
    {
        if (empty($password)) {
            throw new \Exception('Le mot de passe est obligatoire.');
        }

        $len = strlen($password);
        if ($len < 3 || $len > 10) {
            throw new \Exception('La longueur du mot de passe doit être comprise entre 3 et 10 caractères.');
        }

        if ($password !== $confirmation) {
            throw new \Exception('Les deux mots de passe doivent être identiques.');
        }

        return $password;
    }
}
