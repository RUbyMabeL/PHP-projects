<?php
require "functions.php";

$id = null; // Initialize $id variable

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $data = sanitize($_POST);
  $errors = validate($data);

  if (count($errors) === 0) {
    $bool = updateContact($data);

    if ($bool) {
      $id = $data['id']; // Update $id variable with the submitted ID
      header("Location: contact.php?id=$id");
      exit; // Add exit to stop further execution
    }
  } else {
    // Assign the submitted data to variables for prepopulating the form
    $id = $data['id'];
    $name = $data['name'];
    $email = $data['email'];
    $phone = $data['phone'];
    // Assign other contact details to their respective variables

    // Rest of the code
  }
} else if (isset($_GET['id'])) {
  $data = getContact($_GET['id']);

  if (!$data) {
    // go back to index.php
    header("Location: index.php");
    exit; // Add exit to stop further execution
  }

  $id = $_GET['id']; // Update $id variable with the ID from the URL
} else {
  header("Location: index.php");
  exit; // Add exit to stop further execution
}
?>
<!DOCTYPE html>
<html lang="en">
<?php require "head.php"; ?>

<body>
  <main id="app" class="container my-5 bg-white">
    <div class="row justify-content-center">
      <div class="col-8 p-5">
        <?php require "header.php"; ?>
        <section class="row">
          <div class="col-8">
            <h1 class="display-4 mb-3">Update Contact</h1>
            <form method="post" class="bg-light p-4 border border-1">
              <input type="hidden" name="action" value="update">
              <input type="hidden" name="id" value="<?php echo $id ?? ''; ?>">
              <?php require "inputs.php"; ?>
              <button type="submit" class="btn btn-primary">Update Contact</button>
            </form>
          </div>
        </section>
        <section class="row mt-5">
          <div class="col-8 d-flex justify-content-center">
            <form class='form' method='post' action='delete.php'>
              <input type="hidden" name="action" value="delete">
              <input type="hidden" name="id" value="<?php echo $id ?? ''; ?>">
              <button type="submit" class="btn btn-outline-danger">Delete Contact</button>
            </form>
          </div>
        </section>
      </div>
    </div>
  </main>
</body>

</html>