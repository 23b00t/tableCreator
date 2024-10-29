<?php

namespace App\Helpers;

/**
 * Class FilterData
 *
 * Extract POST requestData and match it against predefined attributes
 */
class FilterData
{
    /**
     * @var array $requestData
     */
    private array $requestData;

    /**
     * @param array $requestData
     */
    public function __construct(array $requestData)
    {
        $this->requestData = $requestData;
    }

    /**
     * filter
     *
     * @return array
     */
    public function filter(): array
    {
        $area = $this->requestData['area'];

        // Define attribute arrays for different areas
        $attributesMap = [
            'dataset' => ['datasetName', 'attributes'],
        ];

        // Check if area exists in the attributes map
        if (!isset($attributesMap[$area])) {
            return []; // Return empty if no matching area is found
        }

        $areaAttributes = $attributesMap[$area];

        $sanitizedData = [];
        foreach ($areaAttributes as $attribute) {
            // If the attribute value is an empty string set it to null (neede for rentalTo)
            $value = $this->requestData[$attribute] === '' ? null : $this->requestData[$attribute];
            $sanitizedData[$attribute] = $value;
        }

        // If no requestData matches an empty array is returned
        return $sanitizedData;
    }
}
