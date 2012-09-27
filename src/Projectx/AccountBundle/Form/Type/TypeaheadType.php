<?php

namespace Projectx\AccountBundle\Form\Type;

use Symfony\Component\Form\AbstractType;

class TypeaheadType extends AbstractType {

	public function getParent() {
		return 'text';
	}

	public function getName() {
		return 'typeahead';
	}

}

?>