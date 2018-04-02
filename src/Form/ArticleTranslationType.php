<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use App\Entity\ArticleTranslation;

class ArticleTranslationType extends AbstractType
{
    private $method;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->method = $options['method'];

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event){
            $entity = $event->getData();
            $form = $event->getForm();

            if (!$entity || null === $entity->getId()) {
            } else {
                $form->add('id', HiddenType::class);
            }

            if($this->method != 'DELETE'){
                $form->add('title', TextType::class);
                $form->add('subtitle', TextType::class);
                $form->add('description', TextType::class);
                $form->add('language', HiddenType::class);
                $form->add('content', TextareaType::class);
            }

        });

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ArticleTranslation::class,
        ));
    }
}
