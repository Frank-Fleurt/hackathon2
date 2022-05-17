<?php

namespace App\Form;

use App\Entity\Clients;
use App\Entity\Task;
use App\Entity\Type;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateTimeType::class,[
              'date_widget' => 'single_text',
              'label' => 'Date de début'
            ])
            ->add('limitDate', DateTimeType::class,[
              'date_widget' => 'single_text',
              'label' => 'Date de fin'
            ])
            ->add('all_day', CheckboxType::class, [
              'label' => 'Toute la journée',
	            'required' => false
            ])
            ->add('background_Color', ColorType::class,[
              'label'=> 'Couleur du fond',
              'required' => false,
              'attr' => [
				  'value' => '#ffffff'
              ]
            ])
            ->add('border_color', ColorType::class,[
              'label'=> 'Couleur des bordures',
              'required' => false,
	            'attr' => [
		            'value' => '#000000'
	            ]
            ])
            ->add('text_color', ColorType::class,[
              'label'=> 'Couleur du texte',
              'required' => false,
	          'attr' => [
				  'value' => '#000000'
	          ]
            ])
            ->add('client',EntityType::class,[
              'label'=>'Client',
              'class' => Clients::class,
//              'query_builder'=> function (EntityRepository $er) {
//                return $er->createQueryBuilder('i')->orderBy('i.name','ASC');
//              },
              'choice_label' => 'name'
            ])
            ->add('type', EntityType::class,[
              'label' => 'Catégorie',
              'class' => Type::class,
//              'query_builder'=> function (EntityRepository $er) {
//                return $er->createQueryBuilder('v')->orderBy('v.name','ASC');
//              },
              'choice_label' => 'name',
            ])
          ->add('Envoyer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
