<?php

namespace App\Controllers;

/**
 * Interface: IController
 */
interface IController
{
    /**
     * Constructor must accept an array parameter.
     *
     * @param array $requestData
     */
    public function __construct(array $requestData);

    /**
     * Invoke method must be implemented without arguments.
     *
     * @return array
     */
    public function invoke(): array;

    /**
     * getView
     *
     * @return string
     */
    public function getView(): string;
}
