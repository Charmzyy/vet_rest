<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to the Organization</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                Welcome to the Organization
            </div>
            <div class="card-body">
                <p>
                    Dear {{ $data['user']->firstname }} {{ $data['user']->lastname }},
                </p>
                <p>
                    Thank you for joining our organization! Your account details are as follows:
                </p>
                <ul>
                    <li><strong>Email:</strong> {{ $data['user']->email }}</li>
                    <li><strong>Password:</strong> {{ $data['password'] }}</li>
                </ul>
                <p>
                    Please keep this information secure.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
