<?php

namespace BlockCypher\AppWallet\Presentation\Facade\Dto;

/**
 * Class AddressListDto
 * @package BlockCypher\AppWallet\Presentation\Facade\Dto
 */
class AddressListDto
{
    /**
     * AddressListItemDto[]
     */
    private $addressListItemDtos;

    /**
     * @return AddressListItemDto[]
     */
    public function getAddressListItemDtos()
    {
        return $this->addressListItemDtos;
    }

    /**
     * @param AddressListItemDto[] $addressListItemDtos
     * @return $this
     */
    public function setAddressListItemDtos($addressListItemDtos)
    {
        $this->addressListItemDtos = $addressListItemDtos;
        return $this;
    }
}