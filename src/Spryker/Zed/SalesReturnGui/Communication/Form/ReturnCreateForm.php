<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\SalesReturnGui\Communication\SalesReturnGuiCommunicationFactory getFactory()
 */
class ReturnCreateForm extends AbstractType
{
    public const FIELD_RETURN_ITEMS = 'returnItems';

    public const OPTION_RETURN_REASONS = 'option_return_reasons';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired([
            static::OPTION_RETURN_REASONS,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addReturnItemsCollection($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addReturnItemsCollection(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            static::FIELD_RETURN_ITEMS,
            CollectionType::class,
            [
                'entry_type' => ReturnCreateItemsSubForm::class,
                'entry_options' => [
                    static::OPTION_RETURN_REASONS => $options[static::OPTION_RETURN_REASONS],
                ],
                'label' => false,
            ]
        );

        return $this;
    }
}
