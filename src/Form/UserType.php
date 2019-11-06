<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\File;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'required' => true,
                'label' => 'Nom d\'utilisateur',
                'attr' => [
                    'placeholder' => 'Alex33_game'
                    ]
                ])
           ->add('first_name', TextType::class, [
            'label' => 'Prénom',
            'attr' => [
                'placeholder' => 'Antoine'
                ]
            ])
           ->add('last_name', TextType::class, [
            'label' => 'Nom',
            'attr' => [
                'placeholder' => 'Dupont'
                ]
            ])
           ->add('email', EmailType::class, [
            'required' => true,
            'label' => 'Email',
            'attr' => [
                'placeholder' => 'dupont.antoine@mail.com'
                ]
            ])
            ->add('avatar', FileType::class, [ 'required' => false, 
            'data_class'=>null,
            'constraints' => [
                new File([
                    'maxSize' => '1024k',
                    'mimeTypes' => [
                        'application/png',
                        'application/jpeg',
                        'application/jpg',
                        'application/gif',
                        'application/svg+xml',
                    ],
                    'mimeTypesMessage' => 'Attention: le fichier doit être un gif, jpeg, png de moins de 100Mo',
                ])
            ],
              ]) 
            ->add('submit', SubmitType::class, ['label'=>'Modifier']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'validation_groups' => false,
        ]);
    }
}
