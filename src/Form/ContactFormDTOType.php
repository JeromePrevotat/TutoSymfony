<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class ContactFormDTOType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'empty_data' => '',
                'trim' => true,
                'constraints' => [
                    new Length(['max' => 255]),
                ],
            ])
            ->add('email', EmailType::class, [
                'empty_data' => '',
                'trim' => true,
                'constraints' => [
                    new Length(['max' => 255]),
                ],
            ])
            ->add('recipient', ChoiceType::class, [
                'choices' => [
                    'Comptability' => 'test-comptability@test.test',
                    'R&D' => 'test-rnd@test.test',
                    'Human Resources' => 'test-hr@test.test',
                ],
                'empty_data' => '',
                'trim' => true,
                'constraints' => [
                    new Length(['max' => 255]),
                ],
            ])
            ->add('message', TextareaType::class, [
                'empty_data' => '',
                'trim' => true,
                'sanitize_html' => true,
                'constraints' => [
                    new Length(['max' => 2500]),
                ],
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
