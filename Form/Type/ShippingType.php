<?php

namespace KRG\ShippingBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use KRG\ShippingBundle\Doctrine\DBAL\TransportEnum;
use KRG\ShippingBundle\Entity\ShippingInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShippingType extends AbstractType
{
    /**
     * @var array
     */
    var $transports;

    /**
     * @var ShippingInterface
     */
    private $shippingClass;

    /**
     * ShippingType constructor.
     *
     * @param array $transports
     */
    public function __construct(array $transports)
    {
        $this->transports = $transports;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('transport', ChoiceType::class, array(
                'choices' => TransportEnum::getChoices(array_intersect(TransportEnum::$values, $this->transports)) // TODO: limit "transport" based on config filling
            ))
            ->add('number', TextType::class)
            ->add('reference', TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(array(
                'data_class'  => $this->shippingClass,
            ));
    }

    /**
     * @param ShippingInterface $shippingClass
     */
    public function setShippingClass(ShippingInterface $shippingClass)
    {
        $this->shippingClass = $shippingClass;
    }
}
