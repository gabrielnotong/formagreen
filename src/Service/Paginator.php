<?php

declare(strict_types=1);

namespace App\Service;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Query;
use Exception;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;

/**
 * Used to create pagination on data tables
 */
class Paginator
{
    private int                    $currentPage;
    private int                    $limit = 10;
    private ?string                $routeName;
    private Environment            $twig;
    private string                 $templatePath;
    private Query                  $query;

    const PAGE_NUMBER_MISSING_ERROR = 'You have to specify the parameter page.
            Use setCurrentPage of your paginator service';
    const QUERY_NOT_DEFINED         = 'A query needs to be defined when defining your paginator service';

    public function __construct(
        Environment $twig,
        RequestStack $request,
        string $templatePath
    ) {
        $request            = $request->getCurrentRequest();
        $this->twig         = $twig;
        $this->routeName    = $request ? $request->get('_route') : '';
        $this->templatePath = $templatePath;
    }

    /**
     * @throws Exception
     */
    public function render(): void
    {
        if ($this->getPages() <= 1) {
            return;
        }

        $this->twig->display($this->templatePath, [
            'pages'     => $this->getPages(),
            'page'      => $this->getCurrentPage(),
            'routeName' => $this->routeName,
        ]);
    }

    /**
     * @return array<Entity>
     * @throws Exception
     */
    public function getData(): array
    {
        if (empty($this->currentPage)) {
            throw new Exception(self::PAGE_NUMBER_MISSING_ERROR);
        }

        if (empty($this->query)) {
            throw new Exception(self::QUERY_NOT_DEFINED);
        }

        $offset = $this->currentPage * $this->limit - $this->limit;

        return $this->query
            ->setFirstResult($offset)
            ->setMaxResults($this->limit)
            ->getResult();
    }

    /**
     * @throws Exception
     */
    public function getPages(): int
    {
        if (empty($this->query)) {
            throw new Exception(self::QUERY_NOT_DEFINED);
        }
        $total = count($this->query
            ->setFirstResult(null)
            ->setMaxResults(null)
            ->getResult());

        return (int)ceil($total / $this->limit);
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function setCurrentPage(int $currentPage): self
    {
        $this->currentPage = $currentPage;
        return $this;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function setLimit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    public function getRouteName(): string
    {
        return $this->routeName;
    }

    public function setRouteName(string $routeName): self
    {
        $this->routeName = $routeName;
        return $this;
    }

    public function getTemplatePath(): string
    {
        return $this->templatePath;
    }

    public function setTemplatePath(string $templatePath): self
    {
        $this->templatePath = $templatePath;
        return $this;
    }

    public function setQuery(Query $query): self
    {
        $this->query = $query;

        return $this;
    }
}
