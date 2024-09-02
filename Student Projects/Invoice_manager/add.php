<?php
require "data.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $invoice = sanitize($_POST);
  $errors = validation($invoice);
  $submittedValues = [];

  if (empty($errors)) {
    addInvoice($invoice);
    //$_SESSION["invoices"] = $invoices;

    header("Location: index.php");
    exit(); // Make sure to exit after the redirect
  } else {
    $submittedValues['client'] = $_POST['client'];
    $submittedValues['status'] = $_POST['status'];
    $submittedValues['amount'] = $_POST['amount'];
    $submittedValues['email'] = $_POST['email'];
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Invoice Manager</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
</head>

<body>
  <main class="main p-2">
    <?php require "header.php"; ?>
    <br />
    <h3 class="form-title text-center">New Invoice</h3>
    <form class="form" method="post" action="" id="addinvoice" enctype="multipart/form-data">
      <div class="mb-3 p-2">
        <input type="text" class="form-control" name="client" placeholder="Client Name" value="<?php echo isset($submittedValues['client']) ? $submittedValues['client'] : '' ?>">
        <?php if (isset($errors['client'])) : ?>
          <div class="text-danger"><?php echo $errors['client']; ?></div>
        <?php endif; ?>
      </div>
      <div class="mb-3 p-2">
        <input type="text" class="form-control" name="email" placeholder="Client Email" value="<?php echo isset($submittedValues['email']) ? $submittedValues['email'] : '' ?>">
        <?php if (isset($errors['email'])) : ?>
          <div class="text-danger"><?php echo $errors['email']; ?></div>
        <?php endif; ?>
      </div>
      <div class="mb-3 p-2">
        <input type="text" class="form-control" name="amount" placeholder="Invoice Amount" value="<?php echo isset($submittedValues['amount']) ? $submittedValues['amount'] : '' ?>">
        <?php if (isset($errors['amount'])) : ?>
          <div class="text-danger"><?php echo $errors['amount']; ?></div>
        <?php endif; ?>
      </div>
      <div class="mb-3 p-2">
        <select class="form-select" name="status">
          <option value="">Select a status</option>
          <?php foreach ($statuses as $status)
            if ($status != "all") : ?>
            <option value="<?php echo $status; ?>" <?php
                                                    if (isset($submittedValues['status']) && $submittedValues['status'] == $status) :
                                                    ?> selected <?php endif; ?>><?php echo $status; ?></option>
          <?php endif; ?>
        </select>
        <?php if (isset($errors['status'])) : ?>
          <div class="text-danger"><?php echo $errors['status']; ?></div>
        <?php endif; ?>
      </div>
      <input type="file" class="form-control" name="document" accept=".pdf">
      <div class="mb-3 p-2">
        <button type="submit" name="submit" class="btn btn-primary">Add Invoice</button>
      </div>
    </form>
  </main>

</body>

</html>