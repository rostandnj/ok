<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            
			new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
			new Knp\Bundle\MarkdownBundle\KnpMarkdownBundle(),
            
			new JMS\AopBundle\JMSAopBundle(),
			new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),
			new JMS\DiExtraBundle\JMSDiExtraBundle($this),
			

            new Sdz\ATIBundle\SdzATIBundle(),
            new Sdz\UserBundle\SdzUserBundle(),
			new Ob\HighchartsBundle\ObHighchartsBundle(),
			new Knp\Bundle\SnappyBundle\KnpSnappyBundle(),
			new Ensepar\Html2pdfBundle\EnseparHtml2pdfBundle(),
			
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
			$bundles[] = new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
