<x-mail::message>
# Welcome, {{ $user->name }}!

An account has been created for you on the Project Manager platform.

**Your Login Credentials:**
* **Email:** {{ $user->email }}
* **Temporary Password:** {{ $password }}

Please login and change your password as soon as possible.

<x-mail::button :url="config('app.url') . '/login'">
Login to Your Account
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>