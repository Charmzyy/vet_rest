<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Cancellation</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Custom styles can be added here */
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card mt-5">
                    <div class="card-body">
                        <h4 class="card-title">Appointment Cancellation</h4>
                        <p>Dear Dr. {{ $data['name'] }},</p>
                        <p>We regret to inform you that your upcoming appointment scheduled for {{ $data['book_date'] }} at {{ $data['book_time'] }} has been cancelled.</p>
                        <p>If you have any questions or concerns, please feel free to contact us.</p>
                        <p>Thank you for your understanding.</p>
                        <p>Sincerely,</p>
                        <p>Vetio</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
