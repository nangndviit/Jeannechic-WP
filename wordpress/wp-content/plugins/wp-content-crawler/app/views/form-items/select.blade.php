{{--
    Optional variables:
        bool $sortableSelect  If this is `true`, the "select" element's options will be sortable by the user by clicking
                              a button
--}}

<?php
    /** @var string $name */
    /** @var string|array $optionData */
?>
<div class="input-group">
    <div class="input-container">
        <select name="{{ $name }}" id="{{ $name }}" {{ isset($disabled) && $disabled ? 'disabled' : '' }} tabindex="0">
            <?php $selectedKey = isset($settings[$name]) ? (isset($isOption) && $isOption ? $settings[$name] : $settings[$name][0]) : false; ?>
            @foreach($options as $key => $optionData)
                <?php
                    // If the option data is an array
                    $isArr = is_array($optionData);
                    if ($isArr) {
                        // Get the option name and the dependants if there exists any
                        $optionName = \WPCCrawler\Utils::array_get($optionData, 'name');
                        $dependants = \WPCCrawler\Utils::array_get($optionData, 'dependants');
                    } else {
                        // Otherwise, option data is the name of the option and there is no dependant.
                        $optionName = $optionData;
                        $dependants = null;
                    }
                ?>

                <option value="{{ $key }}" data-order="{{ $loop->index }}"
                    @if($selectedKey && $key == $selectedKey) selected="selected" @endif
                    @if($dependants) data-dependants="{{ $dependants }}" @endif
                >{{ $optionName }}</option>
            @endforeach
        </select>

        {{-- If this should be sortable, add the sorter --}}
        @if($sortableSelect ?? false)
            @include('partials.select-sorter')
        @endif
    </div>
</div>