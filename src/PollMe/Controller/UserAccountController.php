<?php

namespace PollMe\Controller;

use Rock\Http\Request;


class UserAccountController extends BaseController
{
    public function updatePasswordAction(Request $request)
    {
        $this->requireUser();

        return $this->render('update_password.html.twig');
    }

    public function doUpdatePasswordAction(Request $request)
    {
        $this->requireUser();

        $errors = array();
        $user = $request->attributes->get('_user');

        try {
            $password = $this->validatePasswords($request->request->get('updatePassword'), $request->request->get('updatePassword2'));
            $user->setPassword($password);
        } catch (\Exception $e) {
            $errors[] = $e->getMessage();
        }

        if (count($errors) === 0) {
            $user_repository = $this->container['repository.user'];
            $user_repository->persist($user);

            $request->getSession()->getFlashBag()->add('notice', 'Mot de passe modifié !');
            $this->redirect('/');
        } else {
            return $this->render('update_password.html.twig', array(
                'errors' => $errors
            ));
        }
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
