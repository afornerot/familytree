<?php

namespace App\Form;

use App\Entity\Individu;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IndividuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $isNew = $options['isNew'] ?? false;

        $builder
            ->add('nomNaissance', TextType::class, [
                'label' => 'Nom de naissance',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('prenom1', TextType::class, [
                'label' => 'Prénom 1',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('prenom2', TextType::class, [
                'label' => 'Prénom 2',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('prenom3', TextType::class, [
                'label' => 'Prénom 3',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('sexe', ChoiceType::class, [
                'label' => 'Sexe',
                'choices' => [
                    'Homme' => 'M',
                    'Femme' => 'F',
                ],
                'attr' => ['class' => 'form-select'],
            ])
            ->add('lieuNaissance', TextType::class, [
                'label' => 'Lieu de naissance',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('dateNaissance', DateType::class, [
                'label' => 'Date de naissance',
                'required' => false,
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('dateDeces', DateType::class, [
                'label' => 'Date de décès',
                'required' => false,
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('notes', TextareaType::class, [
                'label' => 'Notes',
                'required' => false,
                'attr' => ['class' => 'form-control', 'rows' => 3],
            ])
            ->add('pere', EntityType::class, [
                'label' => 'Père',
                'class' => Individu::class,
                'choice_label' => function (Individu $individu) {
                    return $individu->getNomComplet();
                },
                'required' => false,
                'placeholder' => '-- Sélectionner un père --',
                'attr' => ['class' => 'form-select select2-search'],
            ])
            ->add('mere', EntityType::class, [
                'label' => 'Mère',
                'class' => Individu::class,
                'choice_label' => function (Individu $individu) {
                    return $individu->getNomComplet();
                },
                'required' => false,
                'placeholder' => '-- Sélectionner une mère --',
                'attr' => ['class' => 'form-select select2-search'],
            ])
            ->add('ancetreLointain', EntityType::class, [
                'label' => 'Ancêtre lointain (rattachement)',
                'class' => Individu::class,
                'choice_label' => function (Individu $individu) {
                    return $individu->getNomComplet();
                },
                'required' => false,
                'placeholder' => '-- Aucun ancêtre lointain --',
                'attr' => ['class' => 'form-select select2-search'],
            ])
        ;

        if (!$isNew) {
            $builder->add('photo', HiddenType::class, [
                'label' => false,
                'required' => false,
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Individu::class,
            'isNew' => false,
            'csrf_protection' => false,
        ]);
    }
}
