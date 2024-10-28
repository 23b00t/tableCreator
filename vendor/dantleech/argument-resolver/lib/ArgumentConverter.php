<?php

namespace DTL\ArgumentResolver;

use ReflectionParameter;

interface ArgumentConverter
{
    public function canConvert(ReflectionParameter $parameter, $argument): bool;

    public function convert(ArgumentResolver $resolver, ReflectionParameter $parameter, $argument);
}
