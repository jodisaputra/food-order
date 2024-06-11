<?php

namespace App\Livewire;

use App\Models\Menu;
use Livewire\Component;
use App\Models\Category;
use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class HomePage extends Component
{
    use withPagination;
    use livewireAlert;
    public function addToCart($product_id)
    {
        $total_count = CartManagement::addItemToCart($product_id);
        //send to navbar
        $this->dispatch('update-cart-count', total_count: $total_count)->to(Navbar::class);
        $this->alert('success', 'Menu added to cart successfully!', [
            'position' => 'bottom-end',
            'timer' => 3000,
            'toast' => true,
        ]);
    }
    public function render()
    {
        $categories = Category::where('is_active', 1)->get();
        $menus = Menu::query()->where('is_active', 1);
        return view('livewire.home-page', [
            'categories' => $categories,
            'menus' => $menus->paginate(2),
        ]);
    }
}
