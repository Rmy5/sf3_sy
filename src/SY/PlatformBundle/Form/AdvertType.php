<?php

namespace SY\PlatformBundle\Form;

use SY\PlatformBundle\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdvertType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // pattern arbitraire toutes les catégories qui commencent par D.
//        $pattern = 'D%';

        $pattern = '%'; // no pattern


        $builder
            ->add('date', DateType::class)
            ->add('title', TextType::class)
            ->add('author', TextType::class)
            ->add('content', TextareaType::class)
            ->add('email', EmailType::class)
            ->add('image', ImageType::class, ['required' => false])
            ->add('categories', EntityType::class, array(
                'class'         => 'SYPlatformBundle:Category',
                'choice_label'  => 'name',
                'multiple'      => true,
                'query_builder' => function (CategoryRepository $repository) use ($pattern)
                {
                    return $repository->getLikeQueryBuilder($pattern);
                }
            ))
            ->add('save', SubmitType::class)
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event)
            {
                $advert = $event->getData();

                if ($advert === null) {
                    return;
                }

                if ( ! $advert->getPublished() || $advert->getId() === null) {
                    $event->getForm()
                          ->add('published', CheckboxType::class, ['required' => false]);
                } else {
                    $event->getForm()->remove('published');
                }
            });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SY\PlatformBundle\Entity\Advert'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'sy_platformbundle_advert';
    }


}
