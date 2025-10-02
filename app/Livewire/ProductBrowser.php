<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductBrowser extends Component
{
    use WithPagination;

    public $search = '';
    public $brandFilter = '';
    public $sortBy = 'name';
    public $sortDirection = 'asc';
    public $priceMin = '';
    public $priceMax = '';
    public $perPage = 12;

    protected $queryString = [
        'search' => ['except' => ''],
        'brandFilter' => ['except' => ''],
        'sortBy' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
        'priceMin' => ['except' => ''],
        'priceMax' => ['except' => ''],
    ];

    public function mount()
    {
        $this->search = request('search', '');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedBrandFilter()
    {
        $this->resetPage();
    }

    public function updatedPriceMin()
    {
        $this->resetPage();
    }

    public function updatedPriceMax()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->brandFilter = '';
        $this->priceMin = '';
        $this->priceMax = '';
        $this->sortBy = 'name';
        $this->sortDirection = 'asc';
        $this->resetPage();
    }

    public function addToCart($productId)
    {
        $product = Product::findOrFail($productId);
        
        if (!$product->isInStock()) {
            session()->flash('error', 'Product is out of stock!');
            return;
        }

        // Add to cart logic (you can implement this with session or database)
        $cart = session()->get('cart', []);
        
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity']++;
        } else {
            $cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->current_price,
                'image' => $product->image,
                'quantity' => 1,
            ];
        }
        
        session()->put('cart', $cart);
        session()->flash('success', 'Product added to cart!');
        
        $this->dispatch('cartUpdated');
    }

    public function render()
    {
        $query = Product::with(['brand'])
            ->active()
            ->inStock();

        // Apply search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhere('sku', 'like', '%' . $this->search . '%');
            });
        }

        // Apply brand filter
        if ($this->brandFilter) {
            $query->where('brand_id', $this->brandFilter);
        }

        // Apply price range filter
        if ($this->priceMin) {
            $query->where('price', '>=', $this->priceMin);
        }
        if ($this->priceMax) {
            $query->where('price', '<=', $this->priceMax);
        }

        // Apply sorting
        if ($this->sortBy === 'price') {
            $query->orderBy('price', $this->sortDirection);
        } elseif ($this->sortBy === 'name') {
            $query->orderBy('name', $this->sortDirection);
        } elseif ($this->sortBy === 'created_at') {
            $query->orderBy('created_at', $this->sortDirection);
        }

        $products = $query->paginate($this->perPage);
        $brands = Brand::active()->orderBy('name')->get();

        return view('livewire.product-browser', [
            'products' => $products,
            'brands' => $brands,
        ]);
    }
}