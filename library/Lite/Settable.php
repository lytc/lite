<?php

namespace Lite;

trait Settable
{
    /**
     * @param array $setters
     * @return Settable
     */
    public function applySetter(array $setters, $silent = false)
    {
        foreach ($setters as $setter => $param) {
            $setterMethod = 'set' . ucfirst($setter);
            $callable = array($this, $setterMethod);

            if (!is_callable($callable)) {
                if ($silent) {
                    continue;
                }
                throw new Exception("Call undefined method $setter");
            }

            $this->{$setterMethod}($param);
        }
        return $this;
    }
}