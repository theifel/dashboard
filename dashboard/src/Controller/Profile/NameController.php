<?php

declare(strict_types=1);

namespace App\Controller\Profile;

use App\Model\User\UseCase\Name\Command;
use App\Model\User\UseCase\Name\Form;
use App\Model\User\UseCase\Name\Handler;
use App\ReadModel\User\UserFetcher;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NameController extends AbstractController
{
    private UserFetcher $users;
    private LoggerInterface $logger;

    public function __construct(UserFetcher $users, LoggerInterface $logger)
    {
        $this->users = $users;
        $this->logger = $logger;
    }

    /**
     * @Route("profile/name", name="profile.name")
     */
    public function request(Request $request, Handler $handler): Response
    {
        $user = $this->users->get($this->getUser()->getId());

        $command = Command::fromUser($user);

        $form = $this->createForm(Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                $this->addFlash('success', 'Name is successfully changed.');

                return $this->redirectToRoute('profile');
            } catch (\DomainException $e) {
                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('app/profile/name.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
