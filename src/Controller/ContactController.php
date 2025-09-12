<?php

namespace App\Controller;

use App\DTO\ContactDTO;
use App\Form\ContactType;
use PhpParser\Node\Stmt\TryCatch;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

final class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        $data = new ContactDTO();
        $data->name = 'John Doe';
        $data->email = 'jd@test.test';
        $data->recipient = '';
        $data->message = 'Hello, this is a test message.';

        $form = $this->createForm(ContactType::class, $data);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $errors = $this->SendMail($form, $mailer);
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
        if (empty($data->name) || $data->name === '') {
            $errors[] = 'Name is required';
        }
        if (empty($data->email) || $data->email === '') {
            $errors[] = 'Email is required';
        }
        if (empty($data->recipient) || $data->recipient === '') {
            $errors[] = 'Recipient is required';
        }
        if (empty($data->message) || $data->message === '') {
            $errors[] = 'Message is required';
        }
        if (count($errors) === 0) {
            $email = (new Email())
                ->from($data->email)
                ->to($data->recipient)
                ->text('Name: ' . $data->name . "\n" . 'Message: ' . $data->message);
            try {
                $mailer->send($email);
            } catch (\Exception $e) {
                $errors[] = 'Failed to send email: ' . $e->getMessage();
            }
        }
        return $errors;
    }
}
