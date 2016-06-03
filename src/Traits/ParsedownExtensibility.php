<?php

namespace InfinityNext\Eightdown\Traits;

use \Closure;

trait ParsedownExtensibility
{
    /**
     * Adds a Block closure that handles the start of an element.
     *
     * @param  string  $blockName
     * @param  Closure $closure
     * @return boolean
     */
    public function extendBlock($blockName, Closure $closure)
    {
        $blockName  = ucfirst(strtolower($blockName));
        $methodName = camel_case("block_{$blockName}");

        return $this->bindElementClosure($methodName, $closure);
    }

    /**
     * Adds a Block closure that handles the continuance of a multi-line element.
     *
     * @param  string  $blockName
     * @param  Closure $closure
     * @return boolean
     */
    public function extendBlockContinue($blockName, Closure $closure)
    {
        $blockName  = ucfirst(strtolower($blockName));
        $methodName = camel_case("block_{$blockName}_continue");

        return $this->bindElementClosure($methodName, $closure);
    }

    /**
     * Adds a Block closure that handles the end of an element.
     *
     * @param  string  $blockName
     * @param  Closure $closure
     * @return boolean
     */
    public function extendBlockComplete($blockName, Closure $closure)
    {
        $blockName  = ucfirst(strtolower($blockName));
        $methodName = camel_case("block_{$blockName}_complete");

        return $this->bindElementClosure($methodName, $closure);
    }

    /**
     * Adds an Inline closure that handles the entirety of an inline element.
     *
     * @param  string  $inlineName
     * @param  Closure $closure
     * @return boolean
     */
    public function extendInline($inlineName, Closure $closure)
    {
        $blockName  = ucfirst(strtolower($inlineName));
        $methodName = camel_case("inline_{$inlineName}");

        return $this->bindElementClosure($methodName, $closure);
    }

    /**
     * Adds a closure that handles the start of an element.
     *
     * @param  string  $blockName
     * @param  Closure $closure
     * @return boolean
     */
    protected function bindElementClosure($methodName, Closure $closure)
    {
        $this->elementClosures[$methodName] = $closure->bindTo($this);

        return $this;
    }

    /**
     * Extends the inline parser dictionary.
     *
     * @param
     * @return
     */
    public function addInlineType($Marker, $Type)
    {
        if (!isset($this->InlineTypes[$Marker]))
        {
            $this->InlineTypes[$Marker] = [];
            $this->inlineMarkerList .= $Marker;
        }

        $InlineTypes = &$this->InlineTypes[$Marker];
        array_unshift($InlineTypes, $Type);

        return $this;
    }

    /**
     * Extends the block parser dictionary.
     *
     * @param
     * @return
     */
    public function addBlockType($Marker, $Type)
    {
        if (!isset($this->BlockTypes[$Marker]))
        {
            $this->BlockTypes[$Marker] = [];
        }

        $BlockTypes = &$this->BlockTypes[$Marker];
        array_unshift($BlockTypes, $Type);

        return $this;
    }

    /**
     * Removes any reference to this marker in Parsedown.
     *
     * @param  string  $marker
     * @return Parsedown
     */
    public function removeBlockByMarker($marker)
    {
        $this->disableElementInArray($marker, $this->BlockTypes);

        return $this;
    }

    /**
     * Removes any reference to this block in Parsedown.
     *
     * @param  string  $element
     * @return Parsedown
     */
    public function removeBlockByName($element)
    {
        $this->disableElementInArray($element, $this->BlockTypes);

        return $this;
    }


    /**
     * Removes any reference to this inline marker in Parsedown.
     *
     * @param  string  $marker
     * @return Parsedown
     */
    public function removeInlineByMarker($element)
    {
        $this->disableElementInArray($marker, $this->InlineTypes, $this->inlineMarkerList);

        return $this;
    }

    /**
     * Removes any reference to this inline element in Parsedown.
     *
     * @param  string  $element
     * @return Parsedown
     */
    public function removeInlineByName($element)
    {
        $this->disableElementInArray($element, $this->InlineTypes);

        return $this;
    }


    /**
     * Removes any instance of $element in $itemList's child arrays.
     *
     * @param  string  $element
     * @param  array   $itemList
     * @return void
     */
    protected function disableElementInArray($element, array &$itemList)
    {
        foreach ($itemList as $itemMarker => &$itemElements)
        {
            $itemElements = array_diff($itemElements, [$element]);
        }
    }

    /**
     * Removes any instance of $marker in $itemList's child arrays and $markerString.
     *
     * @param  string  $marker
     * @param  array   $itemList
     * @param  string  $markerList
     * @return void
     */
    protected function disableMarkerInArray($marker, array &$itemList, array &$markerList = array())
    {
        if (isset($itemList[$marker]))
        {
            unset($itemList[$marker]);
        }

        $markerList = str_replace($marker, "", $markerList);
    }
}
