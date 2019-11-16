<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('categories', ChoiceType::class, [
            'placeholder' => 'Catégorie',
            'choices' => [
                'Jeux vidéo' => 'jeux-video',
                'Jeux de société' => 'jeux-de-societe',
                'Dvd' => 'dvd',
                'Rétrogaming' => 'retrogaming',
                'Goodies' => 'goodies']])

            ->add('title', TextType::class, [
                'required' => true,
                'label' => 'Titre de votre annonce',
                'attr' => [
                    'placeholder' => 'Tome 1 Harry Potter'
                    ]
                ])

            ->add('description', TextType::class, [
                'required' => true,
                'label' => 'Description de votre annonce',
                'attr' => [
                    'placeholder' => 'Tome 1 Harry Potter, édition 2005, 265 pages'
                    ]
                ])

            ->add('state', HiddenType::class, [
                'label' => 'Dans quel état est votre article ?',
                'required' => true,
               ])

            ->add('price')

            ->add('attachment', FileType::class, [ 
                'label' => 'Ajoutez des photos',
                'data_class' => null,
                'required' => false
                 ])

            ->add('publishedAt', ChoiceType::class, [
                'label' => 'Publier le ',
                'choices' => [
                    'maintenant' => new \DateTime('now'),
                    'demain' => new \DateTime('+1 day'),
                    'dans 1 semaine' => new \DateTime('+1 week'),
                    'dans 1 mois' => new \DateTime('+1 month'),
                ],
                'required' => false,
                'placeholder' => 'Quand ?',
                ])
                
                ->add('public')

                ->add('location');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
