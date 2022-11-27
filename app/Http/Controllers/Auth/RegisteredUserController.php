<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Payee;
use App\Models\Category;
use App\Models\Transaction;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Creating default Payee, Category, and Transaction
        $payee = Payee::create([
            'user_id'   => $user->id,
            'name'      => 'Acme Company'
        ]);
        $category = Category::create([
            'user_id'   => $user->id,
            'name'      => 'Groceries'
        ]);
        $transaction = Transaction::create([
            'user_id'       => $user->id,
            'trans_date'    => '2022-05-27',
            'payee_id'      => $payee->id,
            'category_id'   => $category->id,
            'orig_detail'   => 'Carrots',
            'orig_amt'      => '13.37',
            'approved'      => 1,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
