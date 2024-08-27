<?php
include 'config.php';

function getProducts() {
    global $conn;
    $result = $conn->query("SELECT * FROM products");
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getProduct($id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function addProduct($conn) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
        $name = $_POST['name'];
        $price = $_POST['price'];
        $description = $_POST['description'];
        $image = $_FILES['image']['name'];
        
        // Cek apakah direktori images ada
        $targetDir = "../assets/images/";
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0755, true);
        }
        
        // Upload file gambar
        $targetFile = $targetDir . basename($image);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $stmt = $conn->prepare("INSERT INTO products (name, price, description, image) VALUES (?, ?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param("sdss", $name, $price, $description, $image);
                
                if ($stmt->execute()) {
                    echo "Product added successfully.";
                    header("Location: index.php");
                    exit();
                } else {
                    echo "Error: " . $stmt->error;
                }
                $stmt->close();
            } else {
                echo "Error preparing statement: " . $conn->error;
            }
        } else {
            echo "Error uploading image.";
        }
    }
}


function updateProduct($conn) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_product'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $price = $_POST['price'];
        $description = $_POST['description'];
        $image = $_FILES['image']['name'];

        $updateQuery = "UPDATE products SET name = ?, price = ?, description = ?";
        if ($image) {
            // Upload file gambar
            $target = "images/" . basename($image);
            move_uploaded_file($_FILES['image']['tmp_name'], $target);
            $updateQuery .= ", image = ?";
        }
        $updateQuery .= " WHERE id = ?";

        $stmt = $conn->prepare($updateQuery);
        if ($image) {
            $stmt->bind_param("sdssi", $name, $price, $description, $image, $id);
        } else {
            $stmt->bind_param("sdsi", $name, $price, $description, $id);
        }
        
        if ($stmt->execute()) {
            echo "Product updated successfully.";
            header("Location: index.php");
                    exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

function deleteProduct($conn) {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // Fetch current product to get the image
        $stmt = $conn->prepare("SELECT image FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $product = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        // Delete the product record
        $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            // Delete the product image from the server if it exists
            if ($product['image']) {
                $imagePath = "images/" . $product['image'];
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            echo "Product deleted successfully.";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>
