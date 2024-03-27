<?php

namespace App\Http\Controllers;

use App\Http\Requests\Basket\BasketStoreRequest;
use App\Models\Basket;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BasketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
//    public function index(): View|Application|Factory
//    {
//        return view('basket.index');}
    /**
     * Показывает корзину покупателя
     */
    public function index(Request $request)
    {
        $basket_id = $request->cookie('basket_id');
        if (!empty($basket_id)) {
            $products = Basket::findOrFail($basket_id)->products;
            return view('basket.index', compact('products'));
        } else {
            abort(404);
        }
    }

    public
    function checkout(): View|Application|Factory
    {
        return view('basket.checkout');
    }

    public
    function add(Request $request, $id): RedirectResponse
    {
        $basket_id = $request->cookie('basket_id');
        $quantity = $request->input('quantity') ?? 1;
        if (empty($basket_id)) {
            // если корзина еще не существует — создаем объект
            $basket = Basket::create();
            $quantity = $basket->products()->updateExistingPivot(
                'product_id',
                ['quantity', $quantity]
            );
            // получаем идентификатор, чтобы записать в cookie
            $basket_id = $basket->id;
        } else {
            // корзина уже существует, получаем объект корзины
            $basket = Basket::findOrFail($basket_id);
            // обновляем поле `updated_at` таблицы `baskets`
            $basket->touch();
        }
        if ($basket->products->contains($id)) {
            // если такой товар есть в корзине — изменяем кол-во
            $pivotRow = $basket->products()->where('product_id', $id)->first()->pivot;
            $quantity = $pivotRow->quantity + $quantity;
            $pivotRow->update(['quantity' => $quantity]);
        } else {
            // если такого товара нет в корзине — добавляем его
            $basket->products()->attach($id, ['quantity' => $quantity]);
        }
        // выполняем редирект обратно на страницу, где была нажата кнопка «В корзину»
        return back()->withCookie(cookie('basket_id', $basket_id, 525600));
    }

    /**
     * Show the form for creating a new resource.
     */
    public
    function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public
    function store(BasketStoreRequest $request): BasketStoreRequest
    {
        return $request;
    }

    /**
     * Display the specified resource.
     */
    public
    function show(basket $basket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public
    function edit(basket $basket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public
    function update(Request $request, basket $basket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public
    function destroy(basket $basket)
    {
        //
    }
}
