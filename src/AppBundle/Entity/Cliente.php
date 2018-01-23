<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Base\BaseClass;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Cliente
 *
 * @Vich\Uploadable
 * @ORM\Table(name="clientes")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ClienteRepository")
 */
class Cliente extends Persona
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\PedidoCabecera", mappedBy="cliente", cascade={"persist"})
     */
    private $pedidos;


    /**
     * Add pedido
     *
     * @param \AppBundle\Entity\PedidoCabecera $pedido
     *
     * @return Cliente
     */
    public function addPedido(\AppBundle\Entity\PedidoCabecera $pedido)
    {
        $this->pedidos[] = $pedido;

        return $this;
    }

    /**
     * Remove pedido
     *
     * @param \AppBundle\Entity\PedidoCabecera $pedido
     */
    public function removePedido(\AppBundle\Entity\PedidoCabecera $pedido)
    {
        $this->pedidos->removeElement($pedido);
    }

    /**
     * Get pedidos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPedidos()
    {
        return $this->pedidos;
    }
}
