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
           ->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'required' => true,
            'first_options' => ['label' => 'Mot de passe'],
            'second_options' => ['label' => 'Confirmation du mot de passe'],
            'mapped' => false,
            'help' => 'Votre mot de passe doit contenir au minimum 8 caractères.',
            'invalid_message' => 'Les mots de passe ne correspondent pas.',
            ])

            ->add('avatar', FileType::class, [ 'required' => false ]) 

            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez prendre connaissance des mentions légales et les cocher pour créer votre compte.'
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class, ['label'=>'Je m\'inscris']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'validation_groups' => false,
        ]);
    }
}
