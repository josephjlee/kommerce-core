<?php
namespace inklabs\kommerce\Action\Product;

use inklabs\kommerce\EntityDTO\PaginationDTO;
use inklabs\kommerce\Lib\Query\QueryInterface;

final class ListProductsQuery implements QueryInterface
{
    /** @var string|null */
    private $queryString;

    /** @var PaginationDTO */
    private $paginationDTO;

    public function __construct(?string $queryString, PaginationDTO $paginationDTO)
    {
        $this->queryString = $queryString;
        $this->paginationDTO = $paginationDTO;
    }

    public function getQueryString(): ?string
    {
        return $this->queryString;
    }

    public function getPaginationDTO(): PaginationDTO
    {
        return $this->paginationDTO;
    }
}
