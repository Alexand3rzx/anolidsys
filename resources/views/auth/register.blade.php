<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>
    <div style="padding: 20px;">
        <h2>Register an Account</h2>

        @if ($errors->any())
            <div style="color: red;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div>
                <label>Name:</label><br>
                <input type="text" name="name" value="{{ old('name') }}" required>
            </div>

            <div style="margin-top: 10px;">
                <label>Email:</label><br>
                <input type="email" name="email" value="{{ old('email') }}" required>
            </div>

            <div style="margin-top: 10px;">
                <label>Password:</label><br>
                <input type="password" name="password" required>
            </div>

            <div style="margin-top: 10px;">
                <label>Confirm Password:</label><br>
                <input type="password" name="password_confirmation" required>
            </div>

            <div style="margin-top: 10px;">
                <label>I am a:</label><br>
                <select name="beneficiary_type" required>
                    <option value="">-- Select --</option>
                    <option value="pregnant">Pregnant</option>
                    <option value="senior">Senior Citizen</option>
                    <option value="normal">Normal Beneficiary</option>
                </select>
            </div>

            <div style="margin-top: 15px;">
                <button type="submit">Register</button>
            </div>
        </form>

        <div style="margin-top: 10px;">
            <a href="{{ route('login') }}">Already have an account? Login</a>
        </div>
    </div>
</body>
</html>
