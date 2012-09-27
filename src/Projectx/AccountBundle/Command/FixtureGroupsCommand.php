<?php
namespace Projectx\AccountBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Projectx\AccountBundle\Entity\AccountGroup;

class FixtureGroupsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('projectxaccount:fixture:groups');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // On récupère l'EntityManager
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
		
		// Liste des comptes à ajouter
		$aAccountGroups = array(
			'Serveur',
			'Administratif',
			'Perso'
		);

        foreach ($aAccountGroups as $strKey => $strName) {
			$output->writeln('Creation du groupe : ' . $strName);
			
			$oAccountGroup = new AccountGroup();
			$oAccountGroup->setName($strName);
			$em->persist($oAccountGroup);
		}

        $output->writeln('Enregistrement des groupes...');

        // On déclenche l'neregistrement
        $em->flush();

        // On retourne 0 pour dire que la commande s'est bien exécutée
        return 0;
    }
}
