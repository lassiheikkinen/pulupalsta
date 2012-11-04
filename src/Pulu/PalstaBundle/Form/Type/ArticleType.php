<?php
namespace Pulu\PalstaBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Pulu\PalstaBundle\Entity\Article;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArticleType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $points = $options['data']->getPoints();
        $defaultPoints = empty($points) ? '1' : $points;
        $builder
            ->add('visits', null, array('label' => 'Vierailuja'))
            ->add('points', null, array('label' => 'Pojoja', 'data' => $defaultPoints))
            ->add('localization', new ArticleLocalizationType(), array('label' => ' '));
    }

    public function getName() {
        return 'article';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Pulu\PalstaBundle\Entity\Article',
            'cascade_validation' => true,
        ));
    }

}