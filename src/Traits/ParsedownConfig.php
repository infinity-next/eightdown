<?php

namespace InfinityNext\Eightdown\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait ParsedownConfig
{
    /**
     * The compiled set of options.
     *
     * @var array
     */
    protected $EightdownConfig = [
        'general' => [
            'keepLineBreaks' => false,
            'parseHTML'      => true,
            'parseURL'       => true,
        ],

        'disable' => [

        ],

        'enable' => [
            'i18n',
        ],

        'markup' => [
            'i18n' => [
                'directionAttr' => true,
            ],
            'quote' => [
                'keepSigns' => false,
            ],
        ],
    ];

    public function __construct(array $config = [])
    {
        $config = array_merge($this->EightdownConfig, $config);
        return $this->config($config);
    }

    /**
     * Configures the instance using an array.
     *
     * @param  array|string  $options  Options to set if array, or option value if string.
     * @return Eightdown|mixed
     */
    public function config($options)
    {
        if (is_array($options))
        {
            $this->EightdownConfig = array_merge($this->EightdownConfig, $options);

            // Set generic parsedown config items.
            $this->setBreaksEnabled( !!$this->config('general.keepLineBreaks') )
                ->setMarkupEscaped( !$this->config('general.parseHTML') )
                ->setUrlsLinked( !!$this->config('general.parseURL') );

            // Disable items
            if (isset($options['disable']))
            {
                foreach ($options['disable'] as $disabledMarkup)
                {
                    $this->removeBlockByName($disabledMarkup);
                    $this->removeInlineByName($disabledMarkup);
                }
            }

            if (isset($options['enable']))
            {
                foreach ($options['enable'] as $enabledMarkup)
                {
                    $enableMarkupMethod = Str::camel("enable_markup_{$enabledMarkup}");

                    if (is_callable([$this, $enableMarkupMethod]))
                    {
                        $this->{$enableMarkupMethod}();
                    }
                }
            }

            return $this;
        }
        else if (is_string($options))
        {
            return Arr::get($this->EightdownConfig, $options);
        }
    }
}
