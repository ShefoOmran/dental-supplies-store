<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
class LoginController extends Controller
{
    public function showLoginForm()
    {
        return Inertia::render('Auth/Login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            if (auth()->user()->is_admin) {
                return redirect()->intended('/admin/dashboard');
            } else {
                return redirect()->intended('/profile');
            }
        }

        return back()->withErrors(['email' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة.'])
                     ->with('error', 'فشل تسجيل الدخول! تأكد من البيانات.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'is_admin' => false,
        ]);

        Auth::login($user);

        return redirect('/profile')->with('success', 'تم تسجيل الحساب بنجاح');
    }

    public function show()
    {
        return Inertia::render('Profile/Index', [
            'user' => auth()->user(),
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'phone' => 'required|string|max:20|unique:users,phone,'.$user->id,
            'current_password' => 'nullable|required_with:password|current_password',
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ], [
            'phone.required' => 'حقل الهاتف مطلوب',
            'phone.unique' => 'رقم الهاتف مستخدم مسبقاً',
            'current_password.required_with' => 'حقل كلمة المرور الحالية مطلوب عند تغيير كلمة المرور',
            'current_password.current_password' => 'كلمة المرور الحالية غير صحيحة',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق',
            'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
        ]);

        $user->phone = $validated['phone'];
        
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }
        
        $user->save();

        return redirect()->route('profile')->with('success', 'تم تحديث البيانات بنجاح!');
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'phone' => 'required|string|max:20|unique:users,phone,' . $user->id,
            'password' => 'nullable|confirmed|min:8',
            'current_password' => 'required_with:password',
        ]);

        // إذا كان المستخدم يريد تغيير كلمة المرور
        if ($request->filled('password')) {
            if (!\Hash::check($request->current_password, $user->password)) {
                return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }
            $user->password = \Hash::make($request->password);
        }

        $user->phone = $data['phone'];
        $user->save();

        return redirect()->route('profile')->with('success', 'Profile updated successfully!');
    }
} 