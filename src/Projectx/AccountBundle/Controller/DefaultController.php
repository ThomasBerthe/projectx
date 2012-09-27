<?php

namespace Projectx\AccountBundle\Controller;

use Projectx\AccountBundle\Entity\Account;
use Projectx\AccountBundle\Form\AccountType;
use Projectx\AccountBundle\Form\AccountHandler;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller {

	/**
	 * Page d'accueil
	 */
	public function indexAction() {
		$em = $this->getDoctrine()->getEntityManager();
		$aAccounts = $em->getRepository('ProjectxAccountBundle:Account')->findAllWithGroup();
		return $this->render('ProjectxAccountBundle::index.html.twig', array('accounts' => $aAccounts));
	}
	
	/**
     * Ajout d'un compte
	 */
	public function addAccountAction() {
		// Création du formulaire
		$oAccount = new Account;
		$oForm = $this->createForm(new AccountType(), $oAccount);
		
		// Traitement du formulaire
		$oFormHandler = new AccountHandler($oForm, $this->get('request'), $this->getDoctrine()->getEntityManager());
		if ($oFormHandler->process()) {
			return $this->redirect($this->generateUrl('Home'));
		}
		
		return $this->render('ProjectxAccountBundle::create.html.twig', array('form' => $oForm->createView()));
	}
	
	/**
     * Modification d'un compte
	 * @param int $id
	 */
	public function updateAccountAction($id) {
		// Création du formulaire
		$em = $this->getDoctrine()->getEntityManager();
		$oAccount = $em->getRepository('ProjectxAccountBundle:Account')->findWithGroup($id);
		if (is_null($oAccount))
			throw $this->createNotFoundException('Compte inexistant!');
		$oForm = $this->createForm(new AccountType(), $oAccount);
		
		// Traitement du formulaire
		$oFormHandler = new AccountHandler($oForm, $this->get('request'), $this->getDoctrine()->getEntityManager());
		if ($oFormHandler->process()) {
			return $this->redirect($this->generateUrl('Home'));
		}
		
		return $this->render('ProjectxAccountBundle::update.html.twig', array('form' => $oForm->createView()));
	}
	
	/**
     * Suppression d'un compte
	 * @param int $id
	 */
	public function deleteAccountAction($id) {
		$em = $this->getDoctrine()->getEntityManager();
		$oAccount = $em->getRepository('ProjectxAccountBundle:Account')->find($id);
		if (is_null($oAccount))
			throw $this->createNotFoundException('Compte inexistant!');
		$em->remove($oAccount);
		$em->flush();
		
		return $this->redirect($this->generateUrl('Home'));
	}

}
