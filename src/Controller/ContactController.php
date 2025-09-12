<?php

namespace App\Controller;

use App\Form\ContactFormDTOType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

final class ContactController extends AbstractController
{
    public function __construct(private readonly MailerInterface $mailer) {}

    #[Route('/contact', name: 'contact')]
    public function contact(Request $request): Response
    {
        $form = $this->createForm(ContactFormDTOType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $errors = $this->SendMail($form, $this->mailer);
            if (count($errors) > 0) {
                $errorMsg = '';
                foreach ($errors as $error) {
                    $errorMsg = $errorMsg . '\n' . $error;
                }
                $this->addFlash('Error while sending email', $errorMsg);
                return $this->redirectToRoute('contact');
            }
            $this->addFlash('success', 'Message sent successfully');
            return $this->redirectToRoute('home');
        }
        return $this->render('contact/contact.html.twig', [
            'form' => $form,
        ]);
    }

    private function SendMail($form, MailerInterface $mailer)
    {
        $data = $form->getData();
        $errors = [];
        if (empty($data['name']) || $data['name'] === '') {
            $errors[] = 'Name is required';
        } elseif (empty($data['email']) || $data['email'] === '') {
            $errors[] = 'Email is required';
        } elseif (empty($data['message']) || $data['message'] === '') {
            $errors[] = 'Message is required';
        }
        if (count($errors) === 0) {
            $email = (new Email())
                ->from($data['email'])
                ->to('test-recipient@test.test')
                ->text('Name: ' . $data['name'] . "\n" . 'Message: ' . $data['message']);
            $mailer->send($email);
        }
        return $errors;
    }
}
