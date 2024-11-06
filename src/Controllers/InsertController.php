<?php

namespace App\Controllers;

use App\Core\ManageTable;
use App\Core\PublicMessageException;
use App\Helpers\FilterData;
use App\Models\Dataset;
use App\Models\DatasetAttribute;
use App\Models\TableRow;

/**
 * Class: InsertController
 *
 * @see BaseController
 */
class InsertController extends BaseController
{
    /**
     * @var array $postData
     */
    private array $postData;

    /**
     * __construct
     *
     * @param array $requestData
     */
    public function __construct(array $requestData)
    {
        parent::__construct($requestData);
        // Extract object attribute values from POST requestData
        $this->postData = (new FilterData($requestData))->filter();
    }

    /**
     * datasetAction
     *
     * @return void
     */
    protected function datasetAction(): void
    {
        if (isset($this->postData['attributes'])) {
            try {
                (new ManageTable(
                    $this->postData['datasetName'],
                    array_values($this->postData['attributes'])
                ))->create();
                $dataset = (new Dataset())->insert([$this->postData['datasetName']]);

                $id = $dataset->getId();

                foreach ($this->postData['attributes'] as $attribute) {
                    (new DatasetAttribute())->insert([$id, $attribute]);
                }
            } catch (PublicMessageException) {
                $this->setView('form');
                throw new PublicMessageException(
                    "Die Tabelle '" . $this->postData['datasetName'] . "' existiert bereits."
                );
            }
        } else {
            $this->setView('form');
            throw new PublicMessageException('Bitte füge Spalten zu deiner Tabelle hinzu!');
        }
    }

    /**
     * tableRowAction
     *
     * @param TableRow $tableRow
     * @return void
     */
    protected function tableRowAction(TableRow $tableRow): void
    {
        $tableRow->insert($this->postData['attributes']);
    }
}
