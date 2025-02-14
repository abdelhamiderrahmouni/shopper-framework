<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Brands;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Shopper\Framework\Http\Livewire\AbstractBaseComponent;
use Shopper\Framework\Repositories\Ecommerce\BrandRepository;
use Shopper\Framework\Traits\WithSeoAttributes;

class Create extends AbstractBaseComponent
{
    use WithSeoAttributes;

    public string $name = '';

    public ?string $website = null;

    public ?string $description = null;

    public bool $is_enabled = true;

    public ?string $fileUrl = null;

    public array $seoAttributes = [
        'name' => 'name',
        'description' => 'description',
        'keywords' => 'keywords',
    ];

    protected $listeners = [
        'trix:valueUpdated' => 'onTrixValueUpdate',
        'shopper:fileUpdated' => 'onFileUpdate',
    ];

    public function onTrixValueUpdate(string $value): void
    {
        $this->description = $value;
    }

    public function onFileUpdate($file): void
    {
        $this->fileUrl = $file;
    }

    public function store(): void
    {
        $this->validate($this->rules());

        $brand = (new BrandRepository)->create([
            'name' => $this->name,
            'slug' => $this->name,
            'website' => $this->website,
            'description' => $this->description,
            'is_enabled' => $this->is_enabled,
            'seo_title' => $this->seoTitle,
            'seo_description' => str_limit($this->seoDescription, 157),
            'seo_keywords' => str_limit($this->seoKeywords, 255),
        ]);

        if ($this->fileUrl) {
            $fileName = uniqid(Str::slug($this->name) . '-', false);

            $brand->addMedia($this->fileUrl)
                ->usingName($fileName)
                ->usingFileName($fileName . '.' . pathinfo($this->fileUrl, PATHINFO_EXTENSION))
                ->toMediaCollection(config('shopper.system.storage.disks.uploads'));
        }

        session()->flash('success', __('Brand successfully added!'));

        $this->redirectRoute('shopper.brands.index');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|max:150|unique:' . shopper_table('brands'),
        ];
    }

    public function render(): View
    {
        return view('shopper::livewire.brands.create');
    }
}
