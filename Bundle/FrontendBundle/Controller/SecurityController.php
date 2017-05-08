<?php

namespace CoreShop\Bundle\FrontendBundle\Controller;

use CoreShop\Bundle\CustomerBundle\Form\Type\CustomerLoginType;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends FrontendController
{
    /**
     * @var AuthenticationUtils
     */
    private $authenticationUtils;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var EngineInterface
     */
    private $templatingEngine;

    /**
     * @param AuthenticationUtils $authenticationUtils
     * @param FormFactoryInterface $formFactory
     * @param EngineInterface $templatingEngine
     */
    public function __construct(
        AuthenticationUtils $authenticationUtils,
        FormFactoryInterface $formFactory,
        EngineInterface $templatingEngine
    ) {
        $this->authenticationUtils = $authenticationUtils;
        $this->formFactory = $formFactory;
        $this->templatingEngine = $templatingEngine;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function loginAction(Request $request)
    {
        $lastError = $this->authenticationUtils->getLastAuthenticationError();
        $lastUsername = $this->authenticationUtils->getLastUsername();

        $form = $this->formFactory->createNamed('', CustomerLoginType::class);

        $renderLayout = $request->get('renderLayout', true);

        return $this->templatingEngine->renderResponse($renderLayout ? '@CoreShopFrontend/Security/login.html.twig' : '@CoreShopFrontend/Security/_login-form.html.twig', [
            'form' => $form->createView(),
            'last_username' => $lastUsername,
            'last_error' => $lastError,
            'target' => $request->get('target', null)
        ]);
    }

    /**
     * @param Request $request
     */
    public function checkAction(Request $request)
    {
        throw new \RuntimeException('You must configure the check path to be handled by the firewall.');
    }

    /**
     * @param Request $request
     */
    public function logoutAction(Request $request)
    {
        throw new \RuntimeException('You must configure the logout path to be handled by the firewall.');
    }
}
