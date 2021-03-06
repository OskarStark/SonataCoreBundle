<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\CoreBundle\Tests\Form\Type;

use Sonata\CoreBundle\Form\FormHelper;
use Sonata\CoreBundle\Form\Type\CollectionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CollectionTypeTest extends TypeTestCase
{
    public function testBuildForm()
    {
        $formBuilder = $this->getMockBuilder('Symfony\Component\Form\FormBuilder')->disableOriginalConstructor()->getMock();
        $formBuilder
            ->expects($this->any())
            ->method('add')
            ->will($this->returnCallback(function ($name, $type = null) {
                if (null !== $type) {
                    $this->assertTrue(class_exists($type), sprintf('Unable to ensure %s is a FQCN', $type));
                }
            }));

        $type = new CollectionType();

        $type->buildForm($formBuilder, array(
            'modifiable' => false,
            'type' => 'Symfony\Component\Form\Extension\Core\Type\TextType',
            'type_options' => array(),
            'pre_bind_data_callback' => null,
            'btn_add' => 'link_add',
            'btn_catalogue' => 'SonataCoreBundle',
        ));
    }

    public function testGetParent()
    {
        $form = new CollectionType();

        $parentRef = $form->getParent();

        $this->assertTrue(class_exists($parentRef), sprintf('Unable to ensure %s is a FQCN', $parentRef));
    }

    public function testGetDefaultOptions()
    {
        $type = new CollectionType();

        FormHelper::configureOptions($type, $optionResolver = new OptionsResolver());

        $options = $optionResolver->resolve();

        $this->assertFalse($options['modifiable']);
        $this->assertSame(
            'Symfony\Component\Form\Extension\Core\Type\TextType',
            $options['type']
        );
        $this->assertSame(0, count($options['type_options']));
        $this->assertSame('link_add', $options['btn_add']);
        $this->assertSame('SonataCoreBundle', $options['btn_catalogue']);
        $this->assertNull($options['pre_bind_data_callback']);
    }
}
