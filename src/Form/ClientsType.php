<?php

namespace App\Form;

use App\Entity\Clients;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;

class ClientsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Name', TextType::class, [
              'label' => 'Nom prénom *',
              'constraints' => [
                new Length([
                  'min' => 2,
                  'max' => 60,
                  'minMessage' => 'Rentrez une valeur ayant entre 2 et 60 caractères',
                  'maxMessage' => 'Rentrez une valeur ayant entre 2 et 60 caractères'
                ])
              ]
            ])
            ->add('age', DateType::class, [
              'label' => 'Date de naissance *',
              'widget' => 'single_text',

            ])
            ->add('mail', EmailType::class, [
              'label' => 'Email',
              'required' => false,
	            'constraints' => [
		            new Length([
			            'min' => 2,
			            'max' => 60,
			            'minMessage' => 'Rentrez une valeur ayant entre 2 et 60 caractères',
			            'maxMessage' => 'Rentrez une valeur ayant entre 2 et 60 caractères'
		            ])
	            ]
            ])
            ->add('adress',TextType::class, [
              'label' => 'Adresse postale *',
	            'constraints' => [
		            new Length([
			            'min' => 2,
			            'max' => 60,
			            'minMessage' => 'Rentrez une valeur ayant entre 2 et 60 caractères',
			            'maxMessage' => 'Rentrez une valeur ayant entre 2 et 60 caractères'
		            ])
	            ]
            ])
            ->add('tel', TelType::class, [
				'label' => 'Téléphone *',
            ])
            ->add('img', FileType::class,[
              'data_class' => null,
              'required' => false,
              'mapped' => false,
              'label' => 'Image du client',
            ])
            ->add('Envoyer', SubmitType::class, [
				'attr' => [
					'class'=> 'button'
				]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Clients::class,
        ]);
    }
}
