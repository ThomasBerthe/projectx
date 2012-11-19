<?php

namespace Projectx\AccountBundle\Controller;

use Projectx\AccountBundle\Entity\Account;
use Projectx\AccountBundle\Form\AccountType;
use Projectx\AccountBundle\Form\AccountHandler;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller {

	/**
	 * Page d'accueil
	 */
	public function indexAction() {
		$em = $this->getDoctrine()->getEntityManager();
		$oPaginator = $this->get('knp_paginator');
		$aAccounts = $oPaginator->paginate(
			$em->getRepository('ProjectxAccountBundle:Account')->findAllWithGroup(),
			$this->get('request')->query->get('page', 1),
			2
		);
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
		$oRequest = $this->get('request');

		// Création du formulaire
		$em = $this->getDoctrine()->getEntityManager();
		$oAccount = $em->getRepository('ProjectxAccountBundle:Account')->findWithGroup($id);
		if (is_null($oAccount))
			throw $this->createNotFoundException('Compte inexistant!');
		$oForm = $this->createForm(new AccountType(), $oAccount);

		// Traitement du formulaire
		$oFormHandler = new AccountHandler($oForm, $oRequest, $this->getDoctrine()->getEntityManager());
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

	/**
     * Synchronisation d'un compte (ajax)
	 * @param int $id
	 */
	public function syncAccountAction($id=0) {
		$oRequest = $this->get('request');
		if ($oRequest->isXmlHttpRequest()) {
			$aRes = array();
			switch ($oRequest->getMethod()) {
				// Create, Update
				case 'POST':
				case 'PUT':
					$aRes = $this->syncSaveAccount($id);
				break;
				// Delete
				case 'DELETE':
					$bDeleted = $this->syncDeleteAccount($id);
					if ($bDeleted)
						$aRes = array('id' => $id);
				break;
				// Fetch
				case 'GET':
					if (empty($id))
						$aRes = $this->syncFetchAllAccounts();
				break;
			}
		}

		// Réponse json
		$oResponse = new Response(json_encode($aRes));
		$oResponse->setStatusCode(200);
		$oResponse->headers->set('Content-Type', 'application/json');
		return $oResponse;
	}

	/**
	 * Sauvegarde d'un compte (ajax)
	 * @param type $id
	 * @return Array $aAccount
	 */
	private function syncSaveAccount($id) {
		$oRequest = $this->get('request');
		$em = $this->getDoctrine()->getEntityManager();
		$aAccount = array();

		// Traitement ajax
		if ($oRequest->isXmlHttpRequest()) {
			$aAccount = json_decode(file_get_contents('php://input'), true);
			if (!empty($aAccount)) {
				$oAccount = new Account;

				// Mise à jour
				if (!empty($id))
					$oAccount = $em->getRepository('ProjectxAccountBundle:Account')->findWithGroup($id);

				if (!empty($oAccount) || empty($id)) {
					$oAccount->setName($aAccount['name']);
					$oAccount->setUrl($aAccount['url']);
					$oAccount->setLogin($aAccount['login']);
					$oAccount->setPassword($aAccount['password']);
					$oAccount->setGroup($em->find('ProjectxAccountBundle:AccountGroup', $aAccount['group']['id']));
					$em->persist($oAccount);
					$em->flush();
					$aAccount = $em->getRepository('ProjectxAccountBundle:Account')->findWithGroup($id, true);
				}
			}
		}

		return array_pop($aAccount);
	}

	/**
	 * Récupération de tous les comptes (ajax)
	 * @return Array $aAccounts
	 */
	private function syncFetchAllAccounts() {
		$oRequest = $this->get('request');
		$em = $this->getDoctrine()->getEntityManager();
		$aAccounts = array();

		// Traitement ajax
		if ($oRequest->isXmlHttpRequest()) {
			$aAccounts = $em->getRepository('ProjectxAccountBundle:Account')->findAllWithGroup(true);
		}

		return $aAccounts;
	}

	/**
	 * Suppression d'un compte (ajax)
	 * @param int $id
	 * @param boolean $bDeleted
	 */
	private function syncDeleteAccount($id) {
		$oRequest = $this->get('request');
		$em = $this->getDoctrine()->getEntityManager();
		$bDeleted = false;

		// Traitement ajax
		if ($oRequest->isXmlHttpRequest()) {
			if (!empty($id))
				$oAccount = $em->getRepository('ProjectxAccountBundle:Account')->findWithGroup($id);

			if (!empty($oAccount)) {
				$em->remove($oAccount);
				$em->flush();
				$bDeleted = true;
			}
		}

		return $bDeleted;
	}

}
