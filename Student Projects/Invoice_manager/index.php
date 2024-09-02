<?php
require "data.php";

if (isset($_GET['status'])) {
  $status = $_GET['status'];

  if ($status !== 'all') {
    $filteredInvoices = array_filter($invoices, function ($invoice) use ($status) {
      return $invoice['status'] === $status;
    });
  } else {
    $filteredInvoices = $invoices;
  }
} else {
  $filteredInvoices = $invoices;
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
    <div>
      <a href="?status=all" class="btn btn-primary">All</a>
      <a href='?status=paid' class="btn btn-primary">Paid</a>
      <a href='?status=pending' class="btn btn-primary">Pending</a>
      <a href='?status=draft' class="btn btn-primary">Draft</a>
    </div>
    <br />

    <?php
    $count = count($filteredInvoices)
    ?>
    <p>There are <?php echo $count ?> invoices.</p>
    <hr />

    <table class="table text-center">
      <thead class="thead-light">
        <tr>
          <th scope="col">Number</th>
          <th scope="col">Name</th>
          <th scope="col">Amount</th>
          <th scope="col">Status</th>
          <th scope="col">Change Info</th>
          <th scope="col">Delete Info</th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($filteredInvoices as $invoice) : ?>
          <tr class='p-2'>
            <td class='p-2'><?php echo '#' . $invoice['number'] ?></td>
            <td class='p-2'><?php echo $invoice['client'] ?></td>
            <td class='p-2'><?php echo '$' . $invoice['amount'] ?></td>
            <?php if ($invoice['status'] == 'paid') {
              echo "<td class='table-success'>{$invoice['status']}</td>";
            } else if ($invoice['status'] == 'pending') {
              echo "<td class='table-primary'>{$invoice['status']}</td>";
            } else if ($invoice['status'] == 'draft') {
              echo "<td class='table-warning'>{$invoice['status']}</td>";
            } ?>
            <td class='p-2'><button class='btn btn-primary'><a class='link-light' href='update.php?number=<?php echo $invoice['number'] ?>'>Edit</a></button>
              <?php if (file_exists('documents/' . $invoice['number'] . '.pdf')) : ?>
                <button class="btn btn-success"><a href="documents/<?php echo $invoice['number']; ?>.pdf" target="_blank">View</a></button>
              <?php endif; ?>
            </td>
            <td>
              <form class='form' method='post' action='delete.php'>
                <input type='hidden' name='number' value='<?php echo $invoice['number'] ?>'>
                <button class='btn btn-danger'>Delete</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </main>
</body>

</html>