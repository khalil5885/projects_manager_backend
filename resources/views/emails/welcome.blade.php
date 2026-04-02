<x-mail::message>
    # Welcome, {{ $user->name }}!

    Your account has been created on the **{{ config('app.name') }}** platform.

    Click the button below to set your password. This link expires in **3 days**.

    <x-mail::button :url="config('app.frontend_url') . '/setup-password?token=' . $token . '&email=' . urlencode($user->email)">
        Set Your Password
    </x-mail::button>

    If you did not expect this email, you can ignore it.

    Thanks,
    {{ config('app.name') }}
</x-mail::message>