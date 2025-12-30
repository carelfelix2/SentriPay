<?php

namespace App\Livewire\Seller;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;

class ProductEditor extends Component
{
    use WithFileUploads;

    public $productId = null;
    public $product;

    // Basic Info
    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('required|string')]
    public $description = '';

    #[Validate('required|numeric|min:0')]
    public $price = '';

    #[Validate('required|integer|min:0')]
    public $stock = '';

    #[Validate('required|string')]
    public $category = '';

    #[Validate('required|in:available,sold_out')]
    public $status = 'available';

    #[Validate('required|in:active,archived')]
    public $archiveStatus = 'active';

    // Media
    public $mainImage;
    public $additionalImages = [];
    public $existingImages = [];
    public $imagesToDelete = [];
    public $videoFile;
    public $existingVideo;

    // Logistics
    #[Validate('nullable|numeric|min:0')]
    public $weight = '';

    #[Validate('nullable|numeric|min:0')]
    public $length = '';

    #[Validate('nullable|numeric|min:0')]
    public $width = '';

    #[Validate('nullable|numeric|min:0')]
    public $height = '';

    // Shipping Couriers
    public $enabledCouriers = [];
    public $availableCouriers = [
        'jne' => 'JNE',
        'tiki' => 'TIKI',
        'pos' => 'POS Indonesia',
        'grab' => 'Grab',
        'gojek' => 'GoJek',
    ];

    // Wholesale
    #[Validate('nullable|numeric|min:0')]
    public $wholesalePrice = '';

    #[Validate('nullable|integer|min:0')]
    public $wholesaleMinQty = 0;

    // Specifications
    public $specifications = [
        'brand' => '',
        'material' => '',
        'color' => '',
        'condition' => 'new',
    ];

    // Variants
    public $hasVariants = false;
    public $variants = [];
    public $variantTypes = [];

    // UI State
    public $showVariantModal = false;

    public function mount($productId = null)
    {
        if ($productId) {
            $this->loadProduct($productId);
        }
    }

    public function loadProduct($id)
    {
        $this->product = Product::where('user_id', Auth::id())->findOrFail($id);
        
        // Fill basic info
        $this->name = $this->product->name;
        $this->description = $this->product->description;
        $this->price = $this->product->price;
        $this->stock = $this->product->stock;
        $this->category = $this->product->category;
        $this->status = $this->product->status;
        $this->archiveStatus = $this->product->archive_status ?? 'active';

        // Load images
        if ($this->product->images_json) {
            $this->existingImages = json_decode($this->product->images_json, true) ?? [];
        } elseif ($this->product->image_path) {
            $this->existingImages = [$this->product->image_path];
        }

        // Load video
        $this->existingVideo = $this->product->video_path;

        // Load logistics
        $this->weight = $this->product->weight;
        $this->length = $this->product->length;
        $this->width = $this->product->width;
        $this->height = $this->product->height;

        // Load couriers
        if ($this->product->enabled_couriers) {
            $this->enabledCouriers = json_decode($this->product->enabled_couriers, true) ?? [];
        } else {
            $this->enabledCouriers = array_keys($this->availableCouriers);
        }

        // Load wholesale
        $this->wholesalePrice = $this->product->wholesale_price;
        $this->wholesaleMinQty = $this->product->wholesale_min_qty ?? 0;

        // Load specifications
        if ($this->product->specifications_json) {
            $this->specifications = json_decode($this->product->specifications_json, true) ?? $this->specifications;
        }

        $this->productId = $id;
    }

    public function addMainImage()
    {
        if ($this->mainImage) {
            $path = $this->mainImage->store('products', 'public');
            $this->existingImages = [$path, ...$this->existingImages];
            $this->mainImage = null;
        }
    }

    public function addAdditionalImage()
    {
        if (count($this->additionalImages) > 0) {
            foreach ($this->additionalImages as $image) {
                $path = $image->store('products', 'public');
                $this->existingImages[] = $path;
            }
            $this->additionalImages = [];
        }
    }

    public function removeImage($index)
    {
        $image = $this->existingImages[$index];
        $this->imagesToDelete[] = $image;
        unset($this->existingImages[$index]);
        $this->existingImages = array_values($this->existingImages);
    }

    public function moveImageUp($index)
    {
        if ($index > 0) {
            $temp = $this->existingImages[$index];
            $this->existingImages[$index] = $this->existingImages[$index - 1];
            $this->existingImages[$index - 1] = $temp;
        }
    }

    public function moveImageDown($index)
    {
        if ($index < count($this->existingImages) - 1) {
            $temp = $this->existingImages[$index];
            $this->existingImages[$index] = $this->existingImages[$index + 1];
            $this->existingImages[$index + 1] = $temp;
        }
    }

    public function addVideo()
    {
        if ($this->videoFile) {
            $this->existingVideo = $this->videoFile->store('videos', 'public');
            $this->videoFile = null;
        }
    }

    public function removeVideo()
    {
        if ($this->existingVideo) {
            $this->imagesToDelete[] = $this->existingVideo;
            $this->existingVideo = null;
        }
    }

    public function toggleCourier($courierId)
    {
        if (in_array($courierId, $this->enabledCouriers)) {
            $this->enabledCouriers = array_filter($this->enabledCouriers, fn($c) => $c !== $courierId);
        } else {
            $this->enabledCouriers[] = $courierId;
        }
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->stock,
            'category' => $this->category,
            'status' => $this->status,
            'archive_status' => $this->archiveStatus,
            'weight' => $this->weight ?: null,
            'length' => $this->length ?: null,
            'width' => $this->width ?: null,
            'height' => $this->height ?: null,
            'enabled_couriers' => json_encode($this->enabledCouriers),
            'wholesale_price' => $this->wholesalePrice ?: null,
            'wholesale_min_qty' => $this->wholesaleMinQty,
            'specifications_json' => json_encode($this->specifications),
        ];

        // Handle images
        if (count($this->existingImages) > 0) {
            $data['images_json'] = json_encode($this->existingImages);
            $data['image_path'] = $this->existingImages[0]; // Set first image as main
        }

        // Handle video
        if ($this->existingVideo) {
            $data['video_path'] = $this->existingVideo;
        }

        if ($this->productId) {
            $product = Product::where('user_id', Auth::id())->findOrFail($this->productId);
            $product->update($data);
            $message = 'Produk berhasil diupdate!';
        } else {
            $data['user_id'] = Auth::id();
            $data['sold'] = 0;
            Product::create($data);
            $message = 'Produk berhasil dibuat!';
        }

        // Delete old files
        foreach ($this->imagesToDelete as $file) {
            Storage::disk('public')->delete($file);
        }

        session()->flash('message', $message);
        redirect()->route('seller.products');
    }

    public function render()
    {
        return view('livewire.seller.product-editor')
            ->layout('components.layouts.app');
    }
}
