<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Recipe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\String\Slugger\AsciiSlugger;

class RecipeType extends AbstractType
{
    public function __construct(private FormListenerFactory $formListenerFactory) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'empty_data' => '',
            ])
            ->add('slug', TextType::class, [
                'required' => false,
                // 'constraints' => new Sequentially([
                //     new Length(min: 3),
                //     new Regex('/^[a-z0-9]+(?:-[a-z0-9]+)*$/')
                // ])
            ])
            ->add('Category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
            ])
            ->add('content', TextType::class, [
                'empty_data' => '',
            ])
            ->add('duration')
            ->add('save', SubmitType::class)
            ->addEventListener(FormEvents::PRE_SUBMIT, $this->formListenerFactory->autoslug('title'))
            ->addEventListener(FormEvents::POST_SUBMIT, $this->formListenerFactory->attachTimestamps())
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
