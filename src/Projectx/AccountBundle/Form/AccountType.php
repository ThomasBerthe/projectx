<?php

namespace Projectx\AccountBundle\Form;

use Symfony\Component\Form\AbstractType;

/**
 * Formulaire AccountType
 */
class AccountType extends AbstractType {

	public function buildForm(\Symfony\Component\Form\FormBuilder $oFormBuilder, array $aOptions) {
		$oFormBuilder
			->add('name', 'text')
			->add('url', 'text')
			->add('login', 'text')
			->add('password', 'text')
			->add('group', 'entity', array(
				'class' => 'ProjectxAccountBundle:AccountGroup'
			));
	}
	
	public function getName() {
		return 'projectx_accountbundle_accounttype';
	}
	
	public function getDefaultOptions(array $aOptions) {
		return array(
			'data_class' => 'Projectx\AccountBundle\Entity\Account'
		);
	}
}

?>
