<?php
namespace Projectx\AccountBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Projectx\AccountBundle\Entity\Account;
use Projectx\AccountBundle\Entity\AccountGroup;

class FixtureAccountsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('projectxaccount:fixture:accounts');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // On récupère l'EntityManager
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
		
		// Liste des comptes à ajouter
		$aAccounts = array(
			array('name' => 'Facebook', 'url' => 'http://www.facebook.com', 'login' => 'berthe.thomas@gmail.com', 'password' => 'qBndLi'),
			array('name' => 'Twitter', 'url' => 'http://www.twitter.com', 'login' => 'berthe.thomas@gmail.com', 'password' => 'uKlmdsdk'),
			array('name' => 'Linkedin', 'url' => 'http://www.linkedin.com', 'login' => 'berthe.thomas@gmail.com', 'password' => 'tsst')
		);

		$oAccountGroup = new AccountGroup();
		$oAccountGroup->setName('Réseaux Sociaux');
        foreach ($aAccounts as $strKey => $aAccount) {
			$output->writeln('Creation du compte : ' . $aAccount['name']);
			
			$oAccount = new Account();
			$oAccount->setName($aAccount['name']);
			$oAccount->setUrl($aAccount['url']);
			$oAccount->setLogin($aAccount['login']);
			$oAccount->setPassword($aAccount['password']);
			$oAccount->setGroup($oAccountGroup);
			$em->persist($oAccount);
		}

        $output->writeln('Enregistrement des comptes...');

        // On déclenche l'neregistrement
        $em->flush();

        // On retourne 0 pour dire que la commande s'est bien exécutée
        return 0;
    }
}
