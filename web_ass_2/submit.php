<!DOCTYPE html>
<html>
<head>
    <title>Submitted Details</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="data-card">
    <h2>Application Submitted Successfully!</h2>
    <h3>Your Details:</h3>
    <p><strong>Full Name:</strong> <?php echo $_POST['fullname']; ?></p>
    <p><strong>Email:</strong> <?php echo $_POST['email']; ?></p>
    <p><strong>Phone:</strong> <?php echo $_POST['phone']; ?></p>
    <p><strong>Date of Birth:</strong> <?php echo $_POST['dob']; ?></p>
    <p><strong>Gender:</strong> <?php echo $_POST['gender']; ?></p>
    <p><strong>Address:</strong> <?php echo nl2br($_POST['address']); ?></p>
</div>

</body>
</html>
