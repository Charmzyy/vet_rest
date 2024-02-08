<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Rescheduling</title>
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
                        <h4 class="card-title">Appointment Rescheduling</h4>
                        <p>Dear Dr. {{ $data['name'] }},</p>
                        <p>We would like to inform you that your upcoming appointment scheduled for {{ $data['book_date'] }}  at {{ $data['book_time'] }}has been rescheduled to New Date & Time.</p>
                        <p>Please let us know, and we will make further arrangements. Otherwise, you will be notified about any subsequent changes.</p>
                        <p>Thank you for your cooperation.</p>
                        <p>Sincerely,</p>
                        <p>Vetio</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
