<?php

declare(strict_types=1);

namespace Doctrine\RST\HTML\Renderers;

use Doctrine\RST\Nodes\SpanNode;
use Doctrine\RST\Nodes\TableNode;
use Doctrine\RST\Renderers\NodeRenderer;
use Doctrine\RST\Templates\TemplateRenderer;
use function count;

class TableNodeRenderer implements NodeRenderer
{
    /** @var TableNode */
    private $tableNode;

    /** @var TemplateRenderer */
    private $templateRenderer;

    public function __construct(TableNode $tableNode, TemplateRenderer $templateRenderer)
    {
        $this->tableNode        = $tableNode;
        $this->templateRenderer = $templateRenderer;
    }

    public function render() : string
    {
        $headers = $this->tableNode->getHeaders();
        $data    = $this->tableNode->getData();

        $tableHeader = [];
        $tableRows   = [];

        if (count($headers) !== 0) {
            foreach ($headers as $k => $isHeader) {
                if (! isset($data[$k])) {
                    continue;
                }

                /** @var SpanNode $col */
                foreach ($data[$k] as $col) {
                    $tableHeader[] = $col->render();
                }

                unset($data[$k]);
            }
        }

        foreach ($data as $k => $row) {
            if ($row === []) {
                continue;
            }

            $tableRow = [];

            /** @var SpanNode $col */
            foreach ($row as $col) {
                $tableRow[] = $col->render();
            }

            $tableRows[] = $tableRow;
        }

        return $this->templateRenderer->render('table.html.twig', [
            'tableHeader' => $tableHeader,
            'tableRows' => $tableRows,
        ]);
    }
}
