<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Sylius\Bundle\ResourceBundle\Controller;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Bundle\ResourceBundle\Controller\EventDispatcherInterface as ControllerEventDispatcherInterface;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvents;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
class EventDispatcherSpec extends ObjectBehavior
{
    function let(EventDispatcherInterface $eventDispatcher)
    {
        $this->beConstructedWith($eventDispatcher);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Sylius\Bundle\ResourceBundle\Controller\EventDispatcher');
    }
    
    function it_implements_event_dispatcher_interface()
    {
        $this->shouldImplement(ControllerEventDispatcherInterface::class);
    }

    function it_dispatches_appriopriate_event_for_a_resource(
        RequestConfiguration $requestConfiguration,
        MetadataInterface $metadata,
        EventDispatcherInterface $eventDispatcher,
        ResourceInterface $resource
    )
    {
        $requestConfiguration->getMetadata()->willReturn($metadata);
        $metadata->getApplicationName()->willReturn('sylius');
        $metadata->getName()->willReturn('product');

        $eventDispatcher->dispatch('sylius.product.pre_create', Argument::type(ResourceControllerEvent::class))->shouldBeCalled();

        $this->dispatch(ResourceControllerEvents::PRE_CREATE, $requestConfiguration, $resource);
    }
}