<?php

namespace Projectx\AccountBundle\Form;

use Symfony\Component\Form\AbstractType;

/**
 * Formulaire AccountType
 */
class AccountGroupType extends AbstractType {

	public function buildForm(\Symfony\Component\Form\FormBuilder $oFormBuilder, array $aOptions) {
		$oFormBuilder
			->add('name', 'text');
	}
	
	public function getName() {
		return 'projectx_accountbundle_accountgrouptype';
	}
	
	public function getDefaultOptions(array $aOptions) {
		return array(
			'data_class' => 'Projectx\AccountBundle\Entity\AccountGroup'
		);
	}
}

?>
