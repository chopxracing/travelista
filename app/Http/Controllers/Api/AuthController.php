<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\User\VerifyMail;
use App\Models\User;
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
        User::create($data);
        return response()->json([
           'user' => $data,
        ]);
    }
    public function verify($id, $hash)
    {
        $user = User::findOrFail($id);

        if (sha1($user->email) !== $hash) {
            return response()->json([
                'message' => 'Ссылка недействительна'
            ], 400);
        }

        $user->email_verified_at = now();
        $user->save();

        return response()->json([
            'message' => 'Email успешно подтвержден!',
            'user' => $user
        ]);
    }
}
