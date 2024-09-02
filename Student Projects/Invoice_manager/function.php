<?php
function sanitize($data)
{
  return array_map(function ($value) {
    return htmlspecialchars(stripslashes(trim($value)));
  }, $data);
}

function validation($invoice)
{
  $fields = ['client', 'email', 'amount', 'status'];
  $errors = [];
  global $statuses;

  foreach ($fields as $field) {
    switch ($field) {
      case 'client':
        if (empty($invoice[$field])) {
          $errors[$field] = 'Client Name is required.';
        } else if (!preg_match('/^[a-zA-Z\s]{1,255}$/', $invoice[$field])) {
          $errors[$field] = 'Client Name should contain only letters and spaces, and cannot exceed 255 characters.';
        }
        break;
      case 'email':
        if (empty($invoice[$field])) {
          $errors[$field] = 'Client Email is required.';
        } else if (!filter_var($invoice[$field], FILTER_VALIDATE_EMAIL)) {
          $errors[$field] = 'Email must be a valid address.';
        }
        break;
      case 'amount':
        if (empty($invoice[$field])) {
          $errors[$field] = 'Amount is required';
        } else if (!is_numeric($invoice[$field]) || !ctype_digit($invoice[$field])) {
          $errors[$field] = 'Invoice Amount must be an integer.';
        }
        break;
      case 'status':
        if (empty($invoice[$field])) {
          $errors[$field] = 'Status is required';
        } else if (!in_array($invoice[$field], $statuses)) {
          $errors[$field] = 'Invoice Status must be either "draft", "pending", or "paid".';
        }
        break;
    }
  }

  return $errors;
}

function saveDocument($number)
{
  $document = $_FILES['document'];
  if ($document['error'] === UPLOAD_ERR_OK) {
    //get file extension
    $ext = pathinfo($document['name'], PATHINFO_EXTENSION);
    $filename = $number . '.' . $ext;

    if (!file_exists('documents/')) {
      mkdir('documents/');
    }

    $dest = 'documents/' . $filename;

    return move_uploaded_file($document['tmp_name'], $dest);
  }
  return false;
}

function getInvoices()
{
  global $db;
  $sql = "SELECT i.number, i.amount, i.client, i.email, s.status FROM invoices AS i
      INNER JOIN statuses AS s ON i.status_id = s.id";
  $result = $db->query($sql);
  $invoices = $result->fetchAll(PDO::FETCH_ASSOC);
  return $invoices;
}

function getInvoice($number)
{
  global $db;
  $sql = "SELECT i.number, i.amount, i.client, i.email, s.status FROM invoices AS i
      INNER JOIN statuses AS s ON i.status_id = s.id
      WHERE number = :number";
  $result = $db->prepare($sql);
  $result->execute([':number' => $number]);
  $invoice = $result->fetch(PDO::FETCH_ASSOC);

  return $invoice;
}

function addInvoice($invoice)
{
  global $db;
  global $statuses;

  $status_id = array_search($invoice['status'], $statuses) + 1;

  $sql = "INSERT INTO invoices (number, client, email, amount, status_id)
        VALUES(:number, :client, :email, :amount, :status_id)";
  $result = $db->prepare($sql);
  $result->execute([
    ':number' => substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5),
    ':client' => $invoice['client'],
    ':email' => $invoice['email'],
    ':amount' => $invoice['amount'],
    ':status_id' => $status_id
  ]);

  saveDocument($invoice['number']);
}

function updateInvoice($invoice)
{
  global $db;
  global $statuses;

  $status_id = array_search($invoice['status'], $statuses) + 1;

  $sql = "UPDATE invoices SET number = :number, client = :client, email = :email, amount = :amount, status_id = :status_id WHERE number = :number";
  $stmt = $db->prepare($sql);
  $stmt->execute([
    'number' => $invoice['number'],
    'client' => $invoice['client'],
    'email' => $invoice['email'],
    'amount' => $invoice['amount'],
    'status_id' => $status_id
  ]);

  saveDocument($invoice['number']);
}

function deleteInvoice($number)
{
  global $db;

  $sql = "DELETE FROM invoices WHERE number = :number";
  $stmt = $db->prepare($sql);
  $stmt->execute(['number' => $number]);

  return $stmt->rowCount();
}
