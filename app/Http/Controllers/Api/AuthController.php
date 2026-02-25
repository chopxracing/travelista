<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\User\VerifyMail;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    // Логин и выдача токена
    public function login(Request $request)
    {
        // Валидация
        $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);

        // Ищем пользователя по телефону
        $user = User::where('phone', $request->phone)->first();

        // Проверка пароля
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Неверные данные'], 401);
        }
        if (!$user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email не подтверждён. Проверьте почту.'
            ], 403);
        }
        // Создание токена
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user' => $user->only(['id', 'name', 'phone']),
            'token' => $token
        ]);
    }

    // Выход — удаляем текущий токен
    public function logout(Request $request)
    {
        $user = $request->user();
        if ($user) {
            $user->currentAccessToken()->delete();
        }

        return response()->json(['message' => 'Вы вышли из системы']);
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'phone' => 'required|string|unique:users',
            'password' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'name' => 'required|string',
            'surname' => 'required|string',
            'last_name' => 'required|string',
            'address' => 'required|string',
            'city_id' => 'required|integer',
            'country_id' => 'required|integer',
            'gender' => 'required|integer',
        ]);
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);

        event(new Registered($user));

        return response()->json([
           'user' => $data,
            'message' => 'Пользователь зарегистрирован. Проверьте email для подтверждения.',
        ]);
    }

    public function verifyEmail(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return response()->json(['message' => 'Неверная ссылка подтверждения'], 400);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email уже подтвержден']);
        }

        $user->markEmailAsVerified();

        return response()->json(['message' => 'Email подтвержден']);
    }
    public function verifyEmailRedirect(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return redirect('http://127.0.0.1:8876/login?status=invalid');
        }

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        // Редирект на фронт с параметром успеха
        return redirect('http://127.0.0.1:8876/login?status=verified');
    }
}
