<?php

namespace Aabosham\LivewireSelect;

use Illuminate\Support\Collection;
use Livewire\Component;

/**
 * Class LivewireSelect
 * @package Aabosham\LivewireSelect
 * @property string $name
 * @property string $placeholder
 * @property mixed $value
 * @property boolean $searchable
 * @property string $searchTerm
 * @property array $dependsOn
 * @property array $dependsOnValues
 * @property boolean $waitForDependenciesToShow
 * @property string $noResultsMessage
 * @property string $selectView
 * @property string $defaultView
 * @property string $searchView
 * @property string $searchInputView
 * @property string $searchOptionsContainer
 * @property string $searchOptionItem
 * @property string $searchSelectedOptionView
 * @property string $searchNoResultsView
 */
class LivewireSelect extends Component
{
    public $name;
    public $placeholder;

    public $value;
    public $optionsValues;

    public $searchable;
    public $searchTerm;

    public $dependsOn;
    public $dependsOnValues;

    public $waitForDependenciesToShow;

    public $noResultsMessage;

    public $selectView;
    public $defaultView;
    public $searchView;
    public $searchInputView;
    public $searchOptionsContainer;
    public $searchOptionItem;
    public $searchSelectedOptionView;
    public $searchNoResultsView;
    public $multiple;

    public function mount($name,
                          $value = [],
                          $placeholder = 'Select an option',
                          $searchable = false,
                          $multiple = false,
                          $dependsOn = [],
                          $dependsOnValues = [],
                          $waitForDependenciesToShow = false,
                          $noResultsMessage = 'No options found',
                          $selectView = 'livewire-select::select',
                          $defaultView = 'livewire-select::default',
                          $searchView = 'livewire-select::search',
                          $searchInputView = 'livewire-select::search-input',
                          $searchOptionsContainer = 'livewire-select::search-options-container',
                          $searchOptionItem = 'livewire-select::search-option-item',
                          $searchSelectedOptionView = 'livewire-select::search-selected-option',
                          $searchNoResultsView = 'livewire-select::search-no-results',
                          $extras = [])
    {
        $this->name = $name;
        $this->placeholder = $placeholder;

        $this->value = $value;

        $this->searchable = $searchable;
        $this->searchTerm = '';

        $this->dependsOn = $dependsOn;

        $this->dependsOnValues = collect($this->dependsOn)
            ->mapWithKeys(function ($key) use ($dependsOnValues) {
                $value = collect($dependsOnValues)->get($key);

                return [
                    $key => $value,
                ];
            })
            ->toArray();

        $this->waitForDependenciesToShow = $waitForDependenciesToShow;

        $this->noResultsMessage = $noResultsMessage;

        $this->selectView = $selectView;
        $this->defaultView = $defaultView;
        $this->searchView = $searchView;
        $this->searchInputView = $searchInputView;
        $this->searchOptionsContainer = $searchOptionsContainer;
        $this->searchOptionItem = $searchOptionItem;
        $this->searchSelectedOptionView = $searchSelectedOptionView;
        $this->searchNoResultsView = $searchNoResultsView;
        $this->multiple = $multiple;

        $this->afterMount($extras);
    }

    public function afterMount($extras = [])
    {
        //
    }

    public function options($searchTerm = null) : Collection
    {
        return collect();
    }

    public function selectedOption($value)
    {
        return null;
    }

    public function notifyValueChanged()
    {
        $this->emit("{$this->name}Updated", [
            'name' => $this->name,
            'value' => $this->value,
        ]);
    }

    public function removeSelectedAll(){
        $this->value = [];
        $this->notifyValueChanged();
    }

    public function removeValue($value){
        $this->value = collect($this->value)->filter(function($item) use($value) {
            if($item['value'] != $value){
                return $value;
            }
        })
        ->unique()
        ->toArray();

        $this->notifyValueChanged();
    }

    public function selectValue($value,$description = null)
    {
        if($this->multiple || count(collect($this->value)->all()) == 0){
            $values = $this->value;

            array_push($values ,[
                'value' => $value,
                'name' => $description,
                'description' => $description
            ]);

            $this->value = collect($values)->unique()->filter()->toArray();
        }
        // $this->value = $value;
        

        if ($this->searchable && $this->value == null) {
            $this->emit('livewire-select-focus-search', ['name' => $this->name]);
        }

        if ($this->searchable && $this->value != null) {
            $this->emit('livewire-select-focus-selected', ['name' => $this->name]);
        }

        $this->notifyValueChanged();
    }

    public function updatedValue()
    {
        $this->selectValue($this->value);
    }

    public function getListeners()
    {
        return collect($this->dependsOn)
            ->mapWithKeys(function ($key) {
                return ["{$key}Updated" => 'updateDependingValue'];
            })
            ->toArray();
    }

    public function updateDependingValue($data)
    {
        $name = $data['name'];
        $value = $data['value'];

        $oldValue = $this->getDependingValue($name);

        $this->dependsOnValues = collect($this->dependsOnValues)
            ->put($name, $value)
            ->toArray();

        if ($oldValue != null && $oldValue != $value) {
            $this->value = null;
            $this->searchTerm = null;
            $this->notifyValueChanged();
        }
    }

    public function hasDependency($name)
    {
        return collect($this->dependsOnValues)->has($name);
    }

    public function getDependingValue($name)
    {
        return collect($this->dependsOnValues)->get($name);
    }

    public function isSearching()
    {
        return !empty($this->searchTerm);
    }

    public function allDependenciesMet()
    {
        return collect($this->dependsOnValues)
            ->reject(function ($value) {
                return $value != null;
            })
            ->isEmpty();
    }

    public function styles()
    {
        return [
            'default' => 'p-2 input w-full appearance-none',

            'searchSelectedOption' => 'bg-blue-700 mx-1 my-2 border border-gray-400 flex items-center',
            'searchSelectedOptionTitle' => 'text-blue-100 text-start px-2',
            'searchSelectedOptionReset' => 'h-4 w-4 text-blue-100 mx-1',

            'search' => 'w-full relative',
            'searchInput' => 'input shadow-sm p-2 w-full border border-gray-400',
            'searchOptionsContainer' => 'bg-gray-100  border border-gray-400 absolute top-0 start-0 mt-12 w-full z-20 h-96 overflow-y-auto',
            'searchContainer' => 'flex flex-wrap border border-gray-400',

            'searchOptionItem' => 'p-3 hover:bg-gray-300 hover:text-gray-700 cursor-pointer',
            'searchOptionItemActive' => 'bg-gray-300 text-black font-medium',
            'searchOptionItemInactive' => 'bg-white text-gray-600',

            'searchNoResults' => 'p-8 w-full bg-gray-100 text-center text-gray-600',
        ];
    }

    public function render()
    {
        if ($this->searchable) {
            if ($this->isSearching()) {
                $options = $this->options($this->searchTerm);
            } else {
                $options = collect();
            }
        } else {
            $options = $this->options($this->searchTerm);
        }

        $this->optionsValues = $options->pluck('value')->toArray();

        if ($this->value != null) {
            $selectedOption = $this->value;
        }

        $shouldShow = $this->waitForDependenciesToShow
            ? $this->allDependenciesMet()
            : true;

        $styles = $this->styles();

        $options = $options->whereNotIn('value',collect($this->value)->pluck('value'));
        
        return view($this->selectView)
            ->with([
                'options' => $options,
                'selectedOption' => $selectedOption ?? null,
                'shouldShow' => $shouldShow,
                'styles' => $styles,
            ]);
    }
}
