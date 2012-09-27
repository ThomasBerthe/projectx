<?php

namespace Projectx\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ProjectxUserBundle extends Bundle {

	public function getParent() {
		return 'FOSUserBundle';
	}

}
