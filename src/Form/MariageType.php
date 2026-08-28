<?php

namespace App\Form;

use App\Entity\Individu;
use App\Entity\Mariage;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MariageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('individu1', EntityType::class, [
                'label' => 'Individu 1',
                'class' => Individu::class,
                'choice_label' => function (Individu $individu) {
                    return $individu->getNomComplet();
                },
                'required' => true,
                'placeholder' => '-- Sélectionner un individu --',
                'attr' => ['class' => 'form-select select2-search'],
            ])
            ->add('individu2', EntityType::class, [
                'label' => 'Individu 2',
                'class' => Individu::class,
                'choice_label' => function (Individu $individu) {
                    return $individu->getNomComplet();
                },
                'required' => true,
                'placeholder' => '-- Sélectionner un individu --',
                'attr' => ['class' => 'form-select select2-search'],
            ])
            ->add('dateMariage', DateType::class, [
                'label' => 'Date de mariage',
                'required' => false,
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('lieuMariage', TextType::class, [
                'label' => 'Lieu de mariage',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('notes', TextareaType::class, [
                'label' => 'Notes',
                'required' => false,
                'attr' => ['class' => 'form-control', 'rows' => 3],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Mariage::class,
            'csrf_protection' => false,
        ]);
    }
}
