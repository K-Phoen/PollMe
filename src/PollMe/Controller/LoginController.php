<?php

namespace PollMe\Controller;

use Rock\Http\Request;


class LoginController extends BaseController
{
    public function loginAction(Request $request)
    {
        $user_repository = $this->container['repository.user'];
        $user = $user_repository->findByCredentials($request->request->get('nickname'), $request->request->get('password'));

        if ($user === null) {
            $request->getSession()->getFlashBag()->add('error', 'Identifiants incorrects ...');
            $this->redirect('/');
        }

        $request->attributes->set('_user', $user);
        $request->getSession()->set('user_id', $user->getId());

        $request->getSession()->getFlashBag()->add('notice', 'Connexion réussie !');
        $this->redirect('/');
    }

    public function logoutAction(Request $request)
    {
        $request->attributes->set('_user', null);
        $request->getSession()->set('user_id', null);

        $request->getSession()->getFlashBag()->add('notice', 'Vous êtes maintenant déconnecté.');
        $this->redirect('/');
    }
}
