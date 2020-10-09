<?php

namespace App\Form;

use App\Entity\Ad;
use App\Form\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AdType extends AbstractType
{
    /**
     * Permet d'avoir la configuration de base d'un champ !
     *
     * @param string $label
     * @param string $placeholder
     * @param array $options
     * @return array
     */
    private function getConfiguration($label, $placeholder, $options = []) {
        return array_merge([
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ]
        ], $options);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, $this->getConfiguration("Nom","Nom"))
            ->add('type', TextType::class, $this->getConfiguration("Race","Indiquez la race"))
            ->add('age', IntegerType::class, $this->getConfiguration("Age", "Indiquez l'âge"))
            ->add('sexe', ChoiceType::class, [
                'label' =>"Genre",
                'choices' => array_flip([
                    'Male',
                    'Femelle'
                ]),
                'expanded' => true
            ])
            ->add('size', ChoiceType::class, [
                'label' =>"Taille",
                'choices' => array_flip([
                    'Petit',
                    'Moyen',
                    'Grand'
                ]),   
            ])
            ->add('city', TextType::class, $this->getConfiguration("Lieu", "Indiquez une ville"))
            ->add('introduction', TextType::class, $this->getConfiguration("Introduction", "Donnez une description globale de l'annonce"))
            ->add('content', TextareaType::class, $this->getConfiguration("Description détaillée", "Mettre le contenu de l'annonce"))
            ->add('slug', TextType::class, $this->getConfiguration("Adresse web", "Taper l'adresse web (automatique", ['required' => false]))
            ->add('coverImage', UrlType::class, $this->getConfiguration("URL de l'image principale", "Donnez l'adresse d'une image"))
            ->add('images', CollectionType::class,
            [
                'entry_type' => ImageType::class,
                'allow_add' => true,
                'allow_delete' => true
            ]
            )
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
