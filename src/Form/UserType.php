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
                'label' => 'Nom d\'utilisateur',
                'attr' => [
                    'placeholder' => 'Alex33_game'
                    ]
                ])
           ->add('first_name', TextType::class, [
            'label' => 'Prénom',
            'attr' => [
                'placeholder' => 'Alexandra'
                ]
            ])
           ->add('last_name', TextType::class, [
            'label' => 'Nom d\'utilisateur',
            'attr' => [
                'placeholder' => 'Rounit'
                ]
            ])
           ->add('email', EmailType::class, [
            'label' => 'Email',
            'attr' => [
                'placeholder' => 'rounit.alex@mail.com'
                ]
            ])
           ->add('password', RepeatedType::class, [
            // instead of being set onto the object directly,
            // this is read and encoded in the controller
            'type' => PasswordType::class,
            'first_options' => ['label' => 'Mot de passe'],
            'second_options' => ['label' => 'Confirmation du mot de passe'],
            'mapped' => false,
            'constraints' => [
                new NotBlank([
                    'message' => 'Please enter a password',
                ]),
                new Length([
                    'min' => 6,
                    'minMessage' => 'Your password should be at least {{ limit }} characters',
                    // max length allowed by Symfony for security reasons
                    'max' => 4096,
                ]),
            ],
        ])
            ->add('avatar', FileType::class, [ 'required' => false ]) 
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class, ['label'=>'Je m\'inscrit', 'attr'=>['class'=>'btn-primary btn-block']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
