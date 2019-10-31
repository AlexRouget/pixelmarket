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
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('categories', ChoiceType::class, [
            'placeholder' => 'Catégorie',
            'choices' => [
                'Jeux vidéo' => 'game',
                'Jeux de société' => 'boardgame',
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
                'label' => 'Courte description de votre annonce',
                'attr' => [
                    'placeholder' => 'Tome 1 Harry Potter, édition 2005, 265 pages'
                    ]
                ])

            ->add('state', ChoiceType::class, [
                'label' => 'Dans quel état est votre article ?',
                'required' => true,
                'expanded' => true,
                'choices' => [
                        'Neuf' => 'new',
                        'Très bon état' => 'very-good',
                        'Bon état' => 'good',
                        'État moyen' => 'bad',
                        'Pour bricoler' => 'very-bad',
                ]
               ])

            ->add('price')

            ->add('attachment', FileType::class, [ 
                'label' => 'Ajoutez des photos',
                'data_class' => null,
                'required' => false
                 ])

            ->add('publishedAt', ChoiceType::class, [
                'label' => 'Publier le :',
                'choices' => [
                    'now' => new \DateTime('now'),
                    'tomorrow' => new \DateTime('+1 day'),
                    '1 week' => new \DateTime('+1 week'),
                    '1 month' => new \DateTime('+1 month'),
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
