<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use App\Entity\Article;
use App\Form\ArticleTranslationType;

class ArticleType extends AbstractType
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
                $form->add('category', ChoiceType::class, array(
                  'choices' => array(
                    'Technical' => 'technical',
                    'Essay' => 'essay',
                    'Note' => 'note'
                  )
                ));
                $form->add('date', DateType::class, array(
                    'widget' => 'single_text'
                ));

                $form->add('translations', CollectionType::class, array(
                  'entry_type' => ArticleTranslationType::class
                ));

                $form->add('status', ChoiceType::class, array(
                  'choices' => array(
                    'Public' => 'public',
                    'Private' => 'private'
                  )
                ));
                $form->add('avatar', FileType::class);
                $form->add('cover', FileType::class);
                if (!$entity || null === $entity->getId()) {
                    $form->add('Create', SubmitType::class);
                } else {
                    $form->add('Save', SubmitType::class);
                }
            } else {
                $form->add('Confirm', SubmitType::class);
            }

        });

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Article::class,
        ));
    }
}
