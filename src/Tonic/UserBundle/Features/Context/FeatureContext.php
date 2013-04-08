<?php

namespace Tonic\UserBundle\Features\Context;

use Symfony\Component\HttpKernel\KernelInterface;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Behat\Event\SuiteEvent;
use Behat\Behat\Exception\ErrorException;

use Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
//   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

/**
 * Feature context.
 */
class FeatureContext extends MinkContext implements KernelAwareInterface
{
    private $kernel;
    private $parameters;

    /**
     * Initializes context with parameters from behat.yml.
     *
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @Then /^I wait (\d+) seconds?$/
     */
    public function waitSeconds($seconds)
    {
        $this->getSession()->wait(1000*$seconds);
    }

    /**
     * @Given /^I am follow "([^"]*)" referral link$/
     */
    public function iAmFollowReferralLink($userEmail)
    {
        $user = $this->getUserFromEmail($userEmail);

        $link = $this->fixStepArgument($this->locatePath('?ref='.$user->getRefId()));
        $this->getSession()->visit($this->locatePath($link));
    }

    /**
     * @Given /^"([^"]*)" should be referral for "([^"]*)"$/
     */
    public function shouldBeReferralFor($childUserEmail, $parentUserEmail)
    {
        $childUser = $this->getUserFromEmail($childUserEmail);
        $parentUser = $this->getUserFromEmail($parentUserEmail);

        if ($childUser->getReferralUser()->getId() != $parentUser->getId()) {
            throw new ErrorException(
                'Warning',
                'Referral '.$parentUserEmail.' not provide '.$childUserEmail,
                'Tonic\UserBundle\Features\Context\FeatureContext',
                '72'
            );
        }
    }


    /**
     * Sets HttpKernel instance.
     * This method will be automatically called by Symfony2Extension ContextInitializer.
     *
     * @param KernelInterface $kernel
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @BeforeSuite
     */
    public static function eraseDataBase(SuiteEvent $event)
    {
        self::build_bootstrap();
        self::showRun("database:drop", "app/console doctrine:database:drop --force  --env=test");
        self::showRun("database:create", "app/console doctrine:database:create --env=test");
        self::showRun("schema:create", "app/console doctrine:schema:create --env=test");

        self::showRun("Changing permissions", "chmod -R 777 app/cache app/logs");
        self::showRun("assets:install", "app/console assets:install --env=test");
        self::showRun("Warming up dev cache", "php app/console cache:warmup --env=test");
        self::showRun("Changing permissions", "chmod -R 777 app/cache app/logs");
    }

    public static function showRun($text, $command, $canFail = false)
    {
        echo "\n* $text\n$command\n";
        passthru($command, $return);
        if (0 !== $return && !$canFail) {
            echo "\n/!\\ The command returned $return\n";
            exit(1);
        }
    }

    public static function build_bootstrap()
    {
        self::showRun('Building bootstrap', 'vendor/sensio/distribution-bundle/Sensio/Bundle/DistributionBundle/Resources/bin/build_bootstrap.php');
    }

    private function getUserFromEmail($email)
    {
        return $this->kernel
            ->getContainer()
            ->get('doctrine')
            ->getManager()
            ->getRepository('UserBundle:User')
            ->findOneByEmail($email);
    }
//
// Place your definition and hook methods here:
//
//    /**
//     * @Given /^I have done something with "([^"]*)"$/
//     */
//    public function iHaveDoneSomethingWith($argument)
//    {
//        $container = $this->kernel->getContainer();
//        $container->get('some_service')->doSomethingWith($argument);
//    }
//
}
