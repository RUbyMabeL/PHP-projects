<?php
require "data.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $invoice = sanitize($_POST);
  $errors = validation($invoice);
  $submittedValues = [];

  if (empty($errors)) {
    $new = [
      'number' => $_GET['number'],
      'amount' => $_POST['amount'],
      'status' => $_POST['status'],
      'client' => $_POST['client'],
      'email' => $_POST['email']
    ];

    updateInvoice($new);

    header("Location: index.php");
    exit();
  } else {
    $submittedValues['client'] = $_POST['client'];
    $submittedValues['status'] = $_POST['status'];
    $submittedValues['amount'] = $_POST['amount'];
    $submittedValues['email'] = $_POST['email'];
  }
} else if (isset($_GET['number'])) {
  $invoice = getInvoice($_GET['number']);

  if (!$invoice) {
    header("Location: index.php");
    exit();
  }
} else {
  header("Location: index.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Invoice</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
</head>

<body>
  <main class="main p-2">
    <?php require "header.php"; ?>
    <br />
    <h3 class="form-title text-center">Edit Invoice</h3>
    <form class="form" method="post" action="" enctype="multipart/form-data">
      <div class="mb-3 p-2">
        <input type="text" class="form-control" name="client" placeholder="Client Name" value="<?php echo isset($submittedValues['client']) ? $submittedValues['client'] : $invoice['client']; ?>">
        <?php if (isset($errors['client'])) : ?>
          <div class="text-danger"><?php echo $errors['client']; ?></div>
        <?php endif; ?>
      </div>
      <div class="mb-3 p-2">
        <input type="text" class="form-control" name="email" placeholder="Client Email" value="<?php echo isset($submittedValues['email']) ? $submittedValues['email'] : $invoice['email']; ?>">
        <?php if (isset($errors['email'])) : ?>
          <div class="text-danger"><?php echo $errors['email']; ?></div>
        <?php endif; ?>
      </div>
      <div class="mb-3 p-2">
        <input type="text" class="form-control" name="amount" placeholder="Invoice Amount" value="<?php echo isset($submittedValues['amount']) ? $submittedValues['amount'] : $invoice['amount']; ?>">
        <?php if (isset($errors['amount'])) : ?>
          <div class="text-danger"><?php echo $errors['amount']; ?></div>
        <?php endif; ?>
      </div>
      <div class="mb-3 p-2">
        <select class="form-select" name="status">
          <option value=''>Select a status</option>
          <?php foreach ($statuses as $status)
            if ($status != "all") : ?>
            <option value="<?php echo $status; ?>" <?php
                                                    echo (isset($submittedValues['status']) && $submittedValues['status'] == $status) || (!isset($submittedValues['status']) && $invoice['status'] == $status) ? "selected" : "";
                                                    ?>> <?php echo $status; ?> <?php endif; ?> </option>
        </select>
        <?php if (isset($errors['status'])) : ?>
          <div class="text-danger"><?php echo $errors['status']; ?></div>
        <?php endif; ?>
      </div>
      <input type="file" class="form-control" name="document" accept=".pdf">
      <div class="mb-3 p-2">
        <button type="submit" class="btn btn-primary">Update Invoice</button>
      </div>
    </form>
  </main>
</body>

</html>