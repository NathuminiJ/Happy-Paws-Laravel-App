<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Display the user's wishlist
     */
    public function index()
    {
        $wishlistItems = Wishlist::with('product.brand')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('wishlist.index', compact('wishlistItems'));
    }

    /**
     * Remove item from wishlist
     */
    public function remove(Request $request)
    {
        $request->validate([
            'wishlist_id' => 'required|exists:wishlists,id'
        ]);

        $wishlistItem = Wishlist::where('id', $request->wishlist_id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$wishlistItem) {
            return response()->json(['error' => 'Wishlist item not found'], 404);
        }

        $wishlistItem->delete();

        return response()->json(['success' => 'Item removed from wishlist']);
    }

    /**
     * Clear entire wishlist
     */
    public function clear()
    {
        Wishlist::where('user_id', Auth::id())->delete();

        return response()->json(['success' => 'Wishlist cleared']);
    }
}
